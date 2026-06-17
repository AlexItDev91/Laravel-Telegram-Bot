(function () {
  "use strict";

  var state = {
    index: null,
    loading: false,
    loaded: false
  };

  function scriptBaseUrl() {
    var script = document.currentScript || document.querySelector('script[src$="local-search.js"]');
    return script ? new URL(".", script.src) : new URL(".", window.location.href);
  }

  function normalize(value) {
    return String(value || "").toLowerCase();
  }

  function escapeHtml(value) {
    return String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  function loadIndex() {
    if (state.loaded || state.loading) {
      return Promise.resolve(state.index || []);
    }

    state.loading = true;

    return fetch(new URL("local-search-index.json", scriptBaseUrl()), { credentials: "same-origin" })
      .then(function (response) {
        if (!response.ok) {
          throw new Error("Search index request failed.");
        }

        return response.json();
      })
      .then(function (records) {
        state.index = Array.isArray(records) ? records : [];
        state.loaded = true;

        return state.index;
      })
      .catch(function () {
        state.index = [];
        state.loaded = true;

        return state.index;
      })
      .finally(function () {
        state.loading = false;
      });
  }

  function snippet(content, terms) {
    var source = String(content || "");
    var lowered = source.toLowerCase();
    var firstIndex = -1;

    terms.some(function (term) {
      firstIndex = lowered.indexOf(term);
      return firstIndex >= 0;
    });

    if (firstIndex < 0) {
      return source.slice(0, 180);
    }

    var start = Math.max(0, firstIndex - 70);
    var end = Math.min(source.length, firstIndex + 150);
    var prefix = start > 0 ? "..." : "";
    var suffix = end < source.length ? "..." : "";

    return prefix + source.slice(start, end) + suffix;
  }

  function score(record, terms) {
    var title = normalize(record.title);
    var content = normalize(record.content);
    var value = 0;

    for (var index = 0; index < terms.length; index += 1) {
      var term = terms[index];

      if (!title.includes(term) && !content.includes(term)) {
        return 0;
      }

      if (title === term) {
        value += 100;
      } else if (title.includes(term)) {
        value += 35;
      }

      value += Math.min(25, content.split(term).length - 1);
    }

    return value;
  }

  function findResults(records, query) {
    var terms = normalize(query).split(/\s+/).filter(Boolean);

    if (terms.length === 0) {
      return [];
    }

    return records
      .map(function (record) {
        return {
          record: record,
          score: score(record, terms),
          snippet: snippet(record.content, terms)
        };
      })
      .filter(function (result) {
        return result.score > 0;
      })
      .sort(function (left, right) {
        return right.score - left.score || String(left.record.title).localeCompare(String(right.record.title));
      })
      .slice(0, 12);
  }

  function render(resultsElement, results, query) {
    resultsElement.hidden = false;

    if (!query.trim()) {
      resultsElement.innerHTML = '<div class="ltb-doc-search__empty">Type to search the docs.</div>';
      return;
    }

    if (results.length === 0) {
      resultsElement.innerHTML = '<div class="ltb-doc-search__empty">No results found.</div>';
      return;
    }

    resultsElement.innerHTML = results.map(function (result) {
      var record = result.record;

      var href = new URL(record.url, scriptBaseUrl()).href;

      return (
        '<a class="ltb-doc-search__result" href="' + escapeHtml(href) + '">' +
        '<span class="ltb-doc-search__result-title">' + escapeHtml(record.title) + '</span>' +
        '<span class="ltb-doc-search__result-snippet">' + escapeHtml(result.snippet) + '</span>' +
        '</a>'
      );
    }).join("");
  }

  function createSearch() {
    var root = document.createElement("section");
    root.className = "ltb-doc-search";
    root.setAttribute("aria-label", "Documentation search");
    root.innerHTML = [
      '<label class="ltb-doc-search__label" for="ltb-doc-search-input">Search documentation</label>',
      '<input id="ltb-doc-search-input" class="ltb-doc-search__input" type="search" autocomplete="off" spellcheck="false" placeholder="Search docs" />',
      '<div class="ltb-doc-search__results" hidden></div>'
    ].join("");

    var input = root.querySelector(".ltb-doc-search__input");
    var results = root.querySelector(".ltb-doc-search__results");
    var timer = 0;

    input.addEventListener("input", function () {
      window.clearTimeout(timer);
      timer = window.setTimeout(function () {
        loadIndex().then(function (records) {
          render(results, findResults(records, input.value), input.value);
        });
      }, 80);
    });

    input.addEventListener("focus", function () {
      loadIndex().then(function (records) {
        render(results, findResults(records, input.value), input.value);
      });
    });

    input.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        results.hidden = true;
        input.blur();
      }
    });

    document.addEventListener("keydown", function (event) {
      if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === "k") {
        event.preventDefault();
        input.focus();
        input.select();
      }
    });

    document.addEventListener("click", function (event) {
      if (!root.contains(event.target)) {
        results.hidden = true;
      }
    });

    document.body.appendChild(root);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", createSearch);
  } else {
    createSearch();
  }
}());
