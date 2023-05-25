let books;



function filterList(checkboxName){
    let filteredBooks;
    let dateRangeFrom= document.getElementById("dateFilterFrom").value;
    let dateRangeTo= document.getElementById("dateFilterTo").value;
    let priceRange= document.getElementById("priceSlider").value;
    let checkboxes= document.querySelectorAll('input[name="' + checkboxName + '"]:checked'),
        values = [];
    Array.prototype.forEach.call(checkboxes, function(el) {
        values.push(el.value);
    });
        if(values.length!=0){
            console.log("values isn't empty");
            filteredBooks= books.filter(books => values.includes(books.genre) );
        }
        else{
            console.log("values is empty");
            filteredBooks= books;
        }
        if (dateRangeFrom==""){
            dateRangeFrom= 0;
        }
        if (dateRangeTo==""){
            dateRangeTo= Infinity;
        }
        filteredBooks= filteredBooks.filter(filteredBooks => filteredBooks.price <= priceRange);
        filteredBooks= filteredBooks.filter(filteredBooks =>filteredBooks.year >= dateRangeFrom && filteredBooks.year <=dateRangeTo);
        return filteredBooks;

    }


    function displayBooks(filteredBooks) {
        const bookList = document.querySelector('#bookList');
        bookList.innerHTML = '';
        for (let i = 0; i < filteredBooks.length; i++) {
            const book = filteredBooks[i];
            if(book.subtitle== null){
                console.log("subtitle is null");
                book.subtitle = "No description available for this book";
            }
            const tr = document.createElement('tr');

            tr.innerHTML = `  
        <td class="number text_center">${i+1}</td>
        <td class="image is-4by3"><img src="${book.thumbnail}" alt=""></td>
        <td class="product w-75" ><strong>${book.title}</strong><br>${book.subtitle}</td>
        <td class="rate text-right">${generateRatingStars(book.averageRating)}</td>
        <td class="price text-right">${ "$" +book.price}</td>
    
    `;
            bookList.appendChild(tr);
        }
    }








function generateRatingStars(ratingValue) {
    let temp= 0;
    let starsHTML = '';
    for (let i = 0; i < ratingValue; i++) {
        if(ratingValue > i && ratingValue < i+1){
            if(ratingValue < i+0.25){
                break;
            }else if(ratingValue > i+0.75){
                starsHTML += '<i class="fa fa-star"></i>';
                temp++;
            }
            else{
                console.log("between the 2 spaces")
                starsHTML += '<i class="fa fa-star-half-o"></i>';
                temp++;
            }
        }
        else {
            starsHTML += '<i class="fa fa-star"></i>';
            temp++;
        }
    }
    return `<span class="d-flex justify-content-between">${starsHTML}</span>`;
}










$(document).ready(function () {
    $("#Searchbar").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#search-addon").click();
            console.log("updated");
        }
    });
    $("#search-addon").click(function ()
    {
        let radioOption = document.querySelector('input[name="inlineRadioOptions"]:checked').value;
        let searchBarValue = document.getElementById("Searchbar").value;
        if (radioOption !== null) {
            // Radio button is checked, access its value
            console.log("Selected option: " + radioOption);
            $.get('search_' + radioOption + '/' + searchBarValue, function (data, status) {
                books= data;
                console.log(books[0]);
                displayBooks(filterList('checkBoxGenre'));
            });
        } else {
            // No radio button is checked
            console.log("No option selected");
        }
    });
});

function changePrice(val){
    document.getElementById("range").innerHTML= "$"+val;
}


$('#filterButton').click(function(){
    displayBooks(filterList('checkBoxGenre'));
});

/*
let radioOption= document.querySelector('input[name="inlineRadioOptions"]:checked').value;
let searchBarValue= document.getElementById("Searchbar").value;
console.log( "radio: "+ radioOption + "searchbar: "+searchBarValue)
if(radioOption!==null) {
    $.get('http://127.0.0.1:8000/search_' + radioOption + '/' + searchBarValue, function (data, status) {
        alert("Data: " + data + "\nStatus: " + status);
        for (let i = 0; i < 8; i++) {
            books[i] = data[i];
        }
    });
}



 */
