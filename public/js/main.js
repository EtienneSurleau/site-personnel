$('.colrealisations').on('click mouseover', function(e) {
    $('.expanded-excerpt').removeClass('expanded-excerpt');
    let classes = e.currentTarget.classList;
    e.currentTarget.classList.add('expanded-excerpt');
});

$('body').scrollspy({ target: '#scrollspy-competences' })
$('.alert').alert()