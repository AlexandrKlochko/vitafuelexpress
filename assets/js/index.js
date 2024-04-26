jQuery(document).ready(function ($) {

    $('#load-more').click(function (e) {
        e.preventDefault();
        const button = $(this)
        const data = {
            'action': 'woo_loadmore',
            'query': woo_loadmore_params.posts, // that's how we get params from wp_localize_script() function
            'page': woo_loadmore_params.cur_page
        }

        $.ajax({ // you can also use $.post here
            url: woo_loadmore_params.ajaxurl, // AJAX handler
            data: data,
            type: 'POST',
            beforeSend: function (xhr) {
                button.text('Loading...') // change the button text, you can also add a preloader image
            },
            success: function (data) {
                if (data) {
                    button.text('Load more')
                    $('.products').append(data)
                    woo_loadmore_params.cur_page = parseInt(woo_loadmore_params.cur_page) + 1

                    if (parseInt(woo_loadmore_params.cur_page) == parseInt(woo_loadmore_params.max_page)) {
                        button.remove() // if last page, remove the button
                    }
                    // you can also fire the "post-load" event here if you use a plugin that requires it
                    // $( document.body ).trigger( 'post-load' );
                } else {
                    button.remove() // if no data, remove the button as well
                }
            }
        })
    })
})