$(document).ready(function () {

        $("#favorite_button").click(function () {
            if (user_id != null) {
                $(this).toggleClass('btn-outline-danger btn-danger');
                $.ajax({
                    type: "POST",
                    url: '/book_like',
                    data: {
                        'book_id': book_id,
                        'user_id': user_id
                    },
                });
            } else {
                alert("Please login to add to favorites.");
            }
        });

    }
)
