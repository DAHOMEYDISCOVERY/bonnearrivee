/**
 * Procédure de stabilisation d'une page avant capture d'écran.
 *
 * Sans elle, deux captures de la *même* page diffèrent : les carrousels défilent
 * seuls, les images se chargent à l'approche du regard, et les repères de la carte
 * ont une animation d'entrée. Comparer un rendu Laravel à son miroir sans figer
 * tout cela revient à mesurer le hasard.
 *
 * À exécuter à l'identique sur les deux pages comparées, via
 * `page.evaluate(...)` (Playwright) ou la console du navigateur.
 *
 * Retourne un état observable — hauteur, nombre d'images chargées, carrousels
 * initialisés — qui doit être identique des deux côtés avant toute capture.
 * S'il ne l'est pas, la comparaison d'images ne veut rien dire.
 */
export default async function stabiliser() {
  // 1. Figer les carrousels sur leur diapositive initiale.
  let carrouselsFiges = 0;
  document.querySelectorAll('.swiper').forEach((element) => {
    const swiper = element.swiper;
    if (!swiper) return;
    swiper.autoplay?.stop();
    swiper.slideTo(swiper.params.initialSlide ?? 0, 0);
    carrouselsFiges += 1;
  });

  // 2. Parcourir la page pour déclencher le chargement différé des images.
  const hauteur = document.body.scrollHeight;
  for (let y = 0; y < hauteur; y += 500) {
    window.scrollTo(0, y);
    await new Promise((r) => setTimeout(r, 150));
  }
  window.scrollTo(0, 0);

  // 3. Laisser les animations d'entrée se terminer, puis recouper l'autoplay
  //    que le défilement a pu relancer.
  await new Promise((r) => setTimeout(r, 3000));
  document.querySelectorAll('.swiper').forEach((el) => el.swiper?.autoplay?.stop());
  await new Promise((r) => setTimeout(r, 1500));

  const images = [...document.images];
  return {
    hauteur,
    carrouselsFiges,
    images: images.length,
    imagesChargees: images.filter((i) => i.complete && i.naturalWidth > 0).length,
    swiperInitialises: document.querySelectorAll('.swiper-initialized').length,
  };
}
