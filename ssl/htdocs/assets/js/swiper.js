const swiper = new Swiper(".swiper", {
    loop: true,
    // 前後の矢印
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev"
    },
    spaceBetween: 30,
    centeredSlides: true,
     autoplay: {
         delay: 3000,
         disableOnInteraction: false,
     },
    breakpoints: {
        // スライドの表示枚数：500px以上の場合
        300: {
            slidesPerView: 1,
        },
        // スライドの表示枚数：769px以上の場合
        769: {
            slidesPerView: "auto",
        },
    }
  });