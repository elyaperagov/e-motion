$(function () {
  //switcher

  $('.switcher').click(function () {
    if ($('.switcher-panel').is(':visible')) {
      $('.switcher-btn').css('opacity', 1)
      $(this).find('.switcher-panel').addClass('switcher-panel-close')
    } else {
      $('.switcher-btn').css('opacity', 0.3)
      $(this).find('.switcher-panel').removeClass('switcher-panel-close')
    }
  })

  // go down

  $('.go-down').click(function (e) {
    fullpage_api.moveSectionDown()
    e.preventDefault()
  })

  $('.go-up').click(function (e) {
    fullpage_api.moveTo(1)
    e.preventDefault()
  })

  $('.go-right').click(function (e) {
    fullpage_api.moveSlideRight()
    e.preventDefault()
  })

  $('.go-left').click(function (e) {
    fullpage_api.moveSlideLeft()
    e.preventDefault()
  })

  // function count up

  Count = function () {
    $('.spec-number-valeur').each(function () {
      const dataNumber = $(this).data('number-valeur')
      $(this).text(dataNumber)
      $(this)
        .prop('Counter', 0)
        .animate(
          {
            Counter: $(this).text(),
          },
          {
            duration: 2500,
            easing: 'swing',
            step: function (now) {
              $(this).text(Math.ceil(now))
            },
          }
        )
    })
  }

  CountOld = function () {
    $('.spec-number-old-valeur').each(function () {
      const dataOldNumber = $(this).data('number-old-valeur')
      $(this).text(dataOldNumber)
      $(this)
        .prop('Counter', 0)
        .animate(
          {
            Counter: $(this).text(),
          },
          {
            duration: 1500,
            easing: 'swing',
            step: function (now) {
              $(this).text(Math.ceil(now))
            },
          }
        )
    })
  }

  // toggle button

  $('.tgl-btn').click(function () {
    if ($('.spec-number-old').is(':visible')) {
      $('.spec-number-old').fadeOut()
    } else {
      $('.spec-number-old').fadeIn()
      Count()
      CountOld()
    }
  })

  // more moto

  $('.more-moto').click(function (e) {
    if ($('.more-moto-info').is(':visible')) {
      $('#spec .go-down').fadeIn()
      $(this).html('+')
      $(this).removeClass('open')
      $('.more-moto-info').fadeOut(function () {
        $('.spec-principales').fadeIn(function () {
          // $('.filter-moto').fadeIn();
        })
      })
      // fullpage_api.setAllowScrolling(true)
    } else {
      $('#spec .go-down').fadeOut()
      $(this).html('-')
      $(this).addClass('open')
      $('.spec-principales').fadeOut(function () {
        $('.more-moto-info').fadeIn(function () {
          // $('.filter-moto').fadeOut();
        })
      })
      // fullpage_api.setAllowScrolling(false)
    }
    e.preventDefault()
  })

  // fullpage

  const myFullpage = new fullpage('#fullpage', {
    controlArrows: false,
    scrollOverflow: false,
    autoScrolling: false,
    fitToSection: false,
    fixedElements: '#privacy_modal',
    normalScrollElements: '.more-moto-info, .modal, .modal-content',
    // paddingTop: '10vh',
    onLeave: function (origin, destination, direction) {
      const loadedSection = this
      if (destination.index == 0) {
        $('.filter-moto').fadeOut()
        fullpage_api.setAllowScrolling(false)
      }
    },
    afterSlideLoad: function (section, origin, destination, direction) {
      const loadedSlide = this

      if (section.index == '1' && destination.index == 0) {
        $('.go-left').fadeOut()
        $('.go-right').fadeIn()
      }

      if (section.index == '1' && destination.index == 1) {
        $('.go-left,.go-right').fadeIn()
      }

      if (section.index == '1' && destination.index == 2) {
        $('.go-right').fadeOut()
        $('.go-left').fadeIn()
      }

      if (section.index == '1' && destination.index == 3) {
        $('.go-left').fadeOut()
        $('.go-right').fadeIn()
      }

      if (section.index == '1' && destination.index == 4) {
        $('.go-left').fadeIn()
        $('.go-right').fadeIn()
      }

      if (section.index == '1' && destination.index == 5) {
        $('.go-left').fadeIn()
        $('.go-right').fadeOut()
      }

      if (section.index == '1') {
        const model = destination.anchor.split(',')[0]
        const type = destination.anchor.split(',')[1]
        const code = destination.anchor.split(',')[2]
        const count = destination.index + 1
        let mt = model
        if (type) {
          mt = model + '-' + type
        }
        if (code) {
          window.VMContact.chosen_product.code = code
        } else {
          window.VMContact.chosen_product.code = ''
        }

        $('.filter-moto li').removeClass('filter-actif')
        $('.filter-moto a[data-key="' + destination.index + '"]')
          .parent()
          .addClass('filter-actif')
        $(
          '.bio,.spec,.moto-info, .slide-focus-content, .slide-focus-info-content, .galerie'
        ).hide()
        $(
          '.bio-' +
            mt +
            ',.moto-info-' +
            mt +
            ', .slide-focus-content-' +
            count +
            ', .slide-focus-info-content-' +
            count +
            ', .galerie-0' +
            count
        ).css('display', 'block')

        $('.spec-' + mt).css('display', 'flex')
        $('.modele-name').text(model + type + ' 2021')
      }
    },
    afterLoad: function (origin, destination, direction) {
      if (destination.index == 3 && direction == 'down') {
        // Count();
        // CountOld();
      }
      if (destination.index == 2 && direction == 'down') {
        $('.go-to-order').fadeIn('visible')
      } else if (destination.index == 1 && direction == 'up') {
        $('.go-to-order').fadeOut('visible')
      }
    },
  })

  fullpage_api.setAllowScrolling(false) // bloquer le scroll au debut (souris)
  fullpage_api.setKeyboardScrolling(false) // bloquer le scroll au debut (clavier)

  $('.go-to-order').click(function (e) {
    e.preventDefault()
    const index = $('.fp-section').length - 1
    fullpage_api.moveTo(index)
  })

  // clic que un choix de moto (epure ou escape)

  $('.moto-choix').click(function () {
    let model = $(this).data('model')
    let param = ''
    $('.filter-moto').fadeOut()
    $('.filter-moto[data-model="' + model + '"]').fadeIn()
    $('.filter-moto li').removeClass('filter-actif')
    if (model == 'epure') {
      fullpage_api.moveTo(2, 1)
    } else if (model == 'escape') {
      fullpage_api.moveTo(2, 3)
    } else {
      fullpage_api.moveTo(2, 5)
    }
    param = '?model=' + model
    fullpage_api.setAllowScrolling(true)
    setTimeout(() => {
      history.pushState(null, null, param)
    }, 0)
  })

  $('.filter-moto a').click(function (e) {
    e.preventDefault()

    let type = $(this).data('type')
    let key = $(this).data('key')
    let model = $(this).closest('.filter-moto').data('model')

    let param = '?model=' + model + '&type=' + type

    $('.filter-moto li').removeClass('filter-actif')
    $(this).parent().addClass('filter-actif')
    fullpage_api.moveTo(2, key)

    setTimeout(() => {
      history.pushState(null, null, param)
    }, 0)
  })

  $(window).on('load', function () {
    if (getUrlParameter('model')) {
      const model = getUrlParameter('model')
      $('.moto-choix[data-model="' + model + '"]').trigger('click')

      if (getUrlParameter('type')) {
        const type = getUrlParameter('type')
        setTimeout(() => {
          $('.filter-moto a[data-type="' + type + '"]').trigger('click')
        }, 1500)
      }
    }
  })

  // focus pieces
  let slides = document.getElementsByClassName('slide-focus-info')
  let backdrop = document.querySelector('.modal-backdrop')
  backdrop.addEventListener('click', () => {
    removeActive(slides)
  })

  document.addEventListener('keydown', function (e) {
    if (e.keyCode === 27) {
      removeActive(slides)
    }
  })

  let addActive = function (elements) {
    ele = event.target
    for (i = 0; i < elements.length; i++) {
      if (elements[i].classList.contains('slide-focus-info--show')) {
        elements[i].classList.remove('slide-focus-info--show')
      }
    }

    ele
      .closest('.fp-slides')
      .nextElementSibling.classList.add('slide-focus-info--show')
    backdrop.classList.add('modal-backdrop-show')
    document.body.classList.add('scroll-off')
  }

  let removeActive = function (elements) {
    for (i = 0; i < elements.length; i++) {
      if (elements[i].classList.contains('slide-focus-info--show')) {
        elements[i].classList.remove('slide-focus-info--show')
      }
    }

    backdrop.classList.remove('modal-backdrop-show')
    document.body.classList.remove('scroll-off')
  }

  $('.slide-focus a').click(function (e) {
    // fullpage_api.moveSlideRight()
    addActive(slides)
    e.preventDefault()
  })

  $('.slide-focus-info a').click(function (e) {
    // fullpage_api.moveSlideRight();
    removeActive(slides)
    e.preventDefault()
  })

  // menu mobile

  $('.btn-open-menu').click(function (e) {
    $('.menu-mobile').toggleClass('menu-mobile-actif')
    e.preventDefault()
  })

  // galerie photos

  $('.section-galerie .col').hover(
    function () {
      $(this).siblings().css('opacity', '0.2')
    },
    function () {
      $('.section-galerie .col').css('opacity', '1')
    }
  )

  //typed text

  const typed_init = function () {
    $('.punchlines').each(function (index, selector) {
      if ($(selector).length) {
        const string = $(selector).data('val')
        if (string) {
          const options = {
            strings: string,
            typeSpeed: 100,
            startDelay: 0,
            backDelay: 500,
            backSpeed: 100,
            loop: true,
          }
          const typed = new Typed(selector, options)
        }
      }
    })
  }
  typed_init()
})
