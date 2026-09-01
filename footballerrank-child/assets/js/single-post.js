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
})();
