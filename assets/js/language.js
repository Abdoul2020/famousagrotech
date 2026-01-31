/**
 * Language Switcher - English / Français
 * Switches between index.html and index-fr.html, about.html and about-fr.html, etc.
 */
(function() {
  "use strict";

  const PAGE_MAP = {
    "index.html": "index-fr.html",
    "index-fr.html": "index.html",
    "about.html": "about-fr.html",
    "about-fr.html": "about.html",
    "services.html": "services-fr.html",
    "services-fr.html": "services.html",
    "contact.html": "contact-fr.html",
    "contact-fr.html": "contact.html",
    "blog.html": "blog-fr.html",
    "blog-fr.html": "blog.html",
    "blog-details.html": "blog-details-fr.html",
    "blog-details-fr.html": "blog-details.html",
    "references.html": "references-fr.html",
    "references-fr.html": "references.html"
  };

  const LANG_STORAGE_KEY = "famousagrotech-lang";

  function getCurrentPage() {
    const path = window.location.pathname;
    const file = path.split("/").pop() || "index.html";
    return file.includes("?") ? file.split("?")[0] : file;
  }

  function isFrenchPage() {
    return getCurrentPage().includes("-fr.");
  }

  function getAlternatePage() {
    const current = getCurrentPage();
    return PAGE_MAP[current] || (isFrenchPage() ? "index.html" : "index-fr.html");
  }

  function initLanguageSwitcher() {
    const codeEl = document.querySelector(".language-code");
    const enLink = document.querySelector('.language-switcher a[data-lang="en"]');
    const frLink = document.querySelector('.language-switcher a[data-lang="fr"]');

    if (!codeEl || !enLink || !frLink) return;

    const currentPage = getCurrentPage();
    const alternatePage = getAlternatePage();
    const isFr = isFrenchPage();

    // Update displayed language code
    codeEl.textContent = isFr ? "FR" : "EN";

    // Set hrefs for both options
    enLink.href = isFr ? alternatePage : currentPage;
    frLink.href = isFr ? currentPage : alternatePage;

    // Update active state
    enLink.classList.toggle("active", !isFr);
    frLink.classList.toggle("active", isFr);

    // Handle click: save preference and navigate (Bootstrap dropdown may prevent default)
    [enLink, frLink].forEach(function(link) {
      link.addEventListener("click", function(e) {
        const targetLang = link.getAttribute("data-lang");
        const targetHref = link.getAttribute("href");
        if (targetHref && targetHref !== "#" && targetHref !== currentPage) {
          try {
            localStorage.setItem(LANG_STORAGE_KEY, targetLang);
          } catch (err) {}
          window.location.href = targetHref;
          e.preventDefault();
        }
      });
    });
  }

  document.addEventListener("DOMContentLoaded", initLanguageSwitcher);
})();
