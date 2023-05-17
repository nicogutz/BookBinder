books= [
    {
        "id": 1,
        "title": "Book 1",
        "author": "Author 1",
        "genre": "Fantasy",
        "price": 15,
        "image": "https://www.bootdey.com/image/400x300/FF8C00",
        "rating": 4.5,
        "description": "This is the product description."
    },
    {
        "id": 2,
        "title": "Book 2",
        "author": "Author 2",
        "genre": "Science-fiction",
        "price": 44,
        "image": "https://www.bootdey.com/image/400x300/5F9EA0",
        "rating": 3.5,
        "description": "This is the product description."
    },
    {
        "id": 3,
        "title": "Book 3",
        "author": "Author 3",
        "genre": "Action & Adventure",
        "price": 39.9,
        "image": "https://www.bootdey.com/image/400x300/5F9EA0",
        "rating": 3.5,
        "description": "This is the product description."
    },
    {
        "id": 4,
        "title": "Book 4",
        "author": "Author 4",
        "genre": "Science-fiction",
        "price": 80,
        "image": "https://www.bootdey.com/image/400x300/5F9EA0",
        "rating": 3.5,
        "description": "This is the product description."
    },
    {
        "id": 5,
        "title": "Book 5",
        "author": "Author 5",
        "genre": "Science-fiction",
        "price": 23.5,
        "image": "https://www.bootdey.com/image/400x300/5F9EA0",
        "rating": 3.5,
        "description": "This is the product description."
    },
    {
        "id": 6,
        "title": "Book 6",
        "author": "Author 6",
        "genre": "Horror",
        "price": 50,
        "image": "https://www.bootdey.com/image/400x300/5F9EA0",
        "rating": 3.5,
        "description": "This is the product description."
    },
    {
        "id": 7,
        "title": "Book 7",
        "author": "Author 7",
        "genre": "Thriller",
        "price": 60.6,
        "image": "https://www.bootdey.com/image/400x300/5F9EA0",
        "rating": 3.5,
        "description": "This is the product description."
    },
    {
        "id": 8,
        "title": "Book 8",
        "author": "Author 8",
        "genre": "Science-fiction",
        "price": 59,
        "image": "https://www.bootdey.com/image/400x300/5F9EA0",
        "rating": 3.5,
        "description": "This is the product description."
    },
    {
        "id": 9,
        "title": "Book 2",
        "author": "Author 2",
        "genre": "Science-fiction",
        "price": 35.2,
        "image": "https://www.bootdey.com/image/400x300/5F9EA0",
        "rating": 3.5,
        "description": "This is the product description."
    },

]

function filterList(checkboxName){
    let filteredBooks;
    let priceRange= document.getElementById("priceSlider");
    let checkboxes= document.querySelectorAll('input[name="' + checkboxName + '"]:checked'),
        values = [];
    Array.prototype.forEach.call(checkboxes, function(el) {
        values.push(el.value);
    });
        if(values.length!=0){
            console.log("values isn't empty");
            console.log(values);
            filteredBooks= books.filter(books => values.includes(books.genre) );
        }
        else{
            console.log("values is empty");
            console.log(checkboxes);
            filteredBooks= books;
        }
        filteredBooks= filteredBooks.filter(filteredBooks => filteredBooks.price <= priceRange.value);

        return filteredBooks;

    }


    function displayBooks(filteredBooks) {
        const bookList = document.querySelector('#bookList');
        bookList.innerHTML = '';

        for (let i = 0; i < filteredBooks.length; i++) {
            const book = filteredBooks[i];
            const tr = document.createElement('tr');
            tr.innerHTML = `  
 
        <td class="number text_center">${i+1}</td>
        <td class="image is-4by3"><img src="${book.image}" alt=""></td>
        <td class="product"><strong>${book.title}</strong><br>${book.description}</td>
        <td class="rate text-right"><span><i class=" fa fa-star"></i><i class=" fa fa-star"></i><i class=" fa fa-star"></i></span></td>
        <td class="price text-right">${ "$" +book.price}</td>
    
    `;
            bookList.appendChild(tr);
        }
    };

$(document).ready(displayBooks(filterList('checkBoxGenre')));


$(document).ready(function (){
    $('#filterButton').click(function(){
        displayBooks(filterList('checkBoxGenre'));
    });
});










