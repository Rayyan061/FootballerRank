(() => {
  const content = document.querySelector('.fr-post-content');
  const toc = document.querySelector('#fr-post-toc');
  if (!content || !toc) return;
  const headings = [...content.querySelectorAll('h2, h3')];
  if (!headings.length) return;
  toc.innerHTML = '';
  headings.forEach((heading, index) => {
    if (!heading.id) heading.id = `section-${index + 1}`;
    const link = document.createElement('a');
    link.href = `#${heading.id}`;
    link.className = `fr-toc-link fr-toc-link--${heading.tagName.toLowerCase()}`;
    link.textContent = heading.textContent.trim();
    toc.appendChild(link);
  });
  const links = [...toc.querySelectorAll('a')];
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      links.forEach((link) => link.classList.toggle('is-active', link.hash === `#${entry.target.id}`));
    });
  }, { rootMargin: '-15% 0px -70% 0px' });
  headings.forEach((heading) => observer.observe(heading));

  headings.forEach((heading) => {
    const label = heading.textContent.trim();
    if (/^Key Takeaways$/i.test(label)) heading.classList.add('fr-key-heading');
    const playerMatch = label.match(/^(\d{1,2})\.\s*(.+)$/);
    if (heading.tagName === 'H2' && playerMatch) {
      heading.classList.add('fr-player-heading');
      heading.innerHTML = `<span class="fr-player-rank">${playerMatch[1].padStart(2, '0')}</span><span>${playerMatch[2]}</span>`;
    }
  });

  const faqTitle = headings.find((heading) => /^(FAQs|Frequently Asked Questions)$/i.test(heading.textContent.trim()));
  if (faqTitle) {
    let item = faqTitle.nextElementSibling;
    while (item && item.tagName !== 'H2') {
      if (item.tagName === 'H3') item.classList.add('fr-faq-heading');
      item = item.nextElementSibling;
    }
  }
})();
