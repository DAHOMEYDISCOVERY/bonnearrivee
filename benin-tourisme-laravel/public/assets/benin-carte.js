
(function() {
    class InteractiveMap {
        constructor(element) {
            this.element = element;
            this.titles = element.querySelectorAll('.map-title');
            this.content = element.querySelector('.map-content');
            this.zones = this.content.querySelectorAll('.zone');

            this.bindEvents();
        }

        bindEvents() {
            this.titles.forEach((title, index) => {
                title.addEventListener('click', (event) => { this.updateMap(event, index) }, true);
            });
        }

        updateMap(event, index) {
            const zoneID = event.target.getAttribute('data-id');

            // Reset
            this.resetMap();

            // Update
            event.target.classList.add('active');
            this.content.querySelector(zoneID).classList.add('active');
            this.content.setAttribute('data-compass-rotate', index);
        }

        resetMap() {
            this.titles.forEach(title => {
                title.classList.remove('active');
            });

            this.zones.forEach(zone => {
                zone.classList.remove('active');
            });
        }
    }

    // Init Map
    const mapSections = document.querySelectorAll('.section-interactive-map');
    if (mapSections.length > 0) {
        for (let i = 0; i < mapSections.length; i++) {
            (function(i) { new InteractiveMap(mapSections[i]) })(i);
        }
    }
}());


/* -- ajout hors-site : navigation au clavier sur les onglets -- */
document.querySelectorAll('.map-title').forEach(function (t) {
  t.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); t.click(); }
  });
});
