// Léonore Figueira — portfolio: menu mobile, apparitions au défilement, visionneuse d'images.
(function () {
	var doc = document.documentElement;

	// Menu mobile
	var toggle = document.querySelector('.nav-toggle');
	if (toggle) {
		toggle.addEventListener('click', function () {
			var open = doc.classList.toggle('nav-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		document.querySelectorAll('.nav a').forEach(function (a) {
			a.addEventListener('click', function () {
				doc.classList.remove('nav-open');
				toggle.setAttribute('aria-expanded', 'false');
			});
		});
	}

	// Apparition douce des blocs
	var items = document.querySelectorAll('.reveal');
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
			});
		}, { threshold: 0.01 });
		items.forEach(function (el) { io.observe(el); });
		// filet de sécurité : tout afficher en bas de page
		window.addEventListener('scroll', function () {
			if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 4) {
				items.forEach(function (el) { el.classList.add('in'); });
			}
		}, { passive: true });
	} else {
		items.forEach(function (el) { el.classList.add('in'); });
	}

	// Visionneuse plein écran pour les galeries
	var imgs = Array.prototype.slice.call(document.querySelectorAll('.gallery img, .p-hero img'));
	if (!imgs.length) return;
	var box = document.createElement('div');
	box.className = 'lightbox';
	box.setAttribute('role', 'dialog');
	box.setAttribute('aria-modal', 'true');
	box.setAttribute('aria-label', 'Image en plein écran');
	box.innerHTML = '<img alt=""><button class="lb-close" type="button">Fermer ✕</button>' +
		'<button class="lb-prev" type="button" aria-label="Image précédente">←</button>' +
		'<button class="lb-next" type="button" aria-label="Image suivante">→</button><span class="lb-count"></span>';
	document.body.appendChild(box);
	var big = box.querySelector('img'), count = box.querySelector('.lb-count'), idx = 0, last = null;

	function show(i) {
		idx = (i + imgs.length) % imgs.length;
		var src = imgs[idx].getAttribute('data-full') || imgs[idx].currentSrc || imgs[idx].src;
		big.src = src;
		big.alt = imgs[idx].alt;
		count.textContent = (idx + 1) + ' / ' + imgs.length;
	}
	function open(i) { last = document.activeElement; show(i); box.classList.add('open'); doc.style.overflow = 'hidden'; box.querySelector('.lb-close').focus(); }
	function close() { box.classList.remove('open'); doc.style.overflow = ''; if (last) last.focus(); }

	imgs.forEach(function (img, i) {
		img.style.cursor = 'zoom-in';
		img.setAttribute('tabindex', '0');
		img.addEventListener('click', function () { open(i); });
		img.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(i); } });
	});
	box.querySelector('.lb-close').addEventListener('click', close);
	box.querySelector('.lb-prev').addEventListener('click', function () { show(idx - 1); });
	box.querySelector('.lb-next').addEventListener('click', function () { show(idx + 1); });
	box.addEventListener('click', function (e) { if (e.target === box) close(); });
	document.addEventListener('keydown', function (e) {
		if (!box.classList.contains('open')) return;
		if (e.key === 'Escape') close();
		if (e.key === 'ArrowLeft') show(idx - 1);
		if (e.key === 'ArrowRight') show(idx + 1);
	});
	var x0 = null;
	box.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
	box.addEventListener('touchend', function (e) {
		if (x0 === null) return;
		var dx = e.changedTouches[0].clientX - x0;
		if (Math.abs(dx) > 50) show(idx + (dx < 0 ? 1 : -1));
		x0 = null;
	});
})();
