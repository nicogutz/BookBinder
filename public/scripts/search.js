let books;
let filteredBooks;
let currentPage = 1;
let pageNav = 1;




/*____________________________________DISPLAY BOOKS_______________________________________*/


    function displayBooks(filteredBooks) {
        enableDisable();
        const bookList = document.querySelector('#bookList');
        bookList.innerHTML = '';
        for (let i = 0; i < 10; i++) {
            if((i + (currentPage-1) * 10) > filteredBooks.length - 1){
                break;
            }
            else {
                const book = filteredBooks[i + (currentPage - 1) * 10];
                const tr = document.createElement('tr');

                tr.innerHTML = `  
        <td class="number text_center">${i + 1}</td>
        <td class="image is-4by3"><img src="${book.thumbnail}" alt=""></td>
        <td class="product w-75" ><strong>${book.title}</strong><br>${book.author}</td>
        <td class="rate text-right">${generateRatingStars(book.averageRating)}</td>
        <td class="price text-right">${"$" + book.price}</td>
    
    `;
                tr.addEventListener('click', function () {
                    // Define the URL you want to navigate to
                    // Navigate to the new link
                    window.location.href = '/book_info/' + book.id;
                });
                bookList.appendChild(tr);


            }
        }
    }


/* STAR RATING */
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
                starsHTML += '<i class="fa fa-star-half-o"></i>';
                temp++;
            }
        }
        else {
            starsHTML += '<i class="fa fa-star"></i>';
            temp++;
        }
    }
    for (let i=0; i<(5-temp); i++){
        starsHTML += '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="13" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">\n' +
            '  <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>\n' +
            '</svg>'
    }
    return `<span class="d-flex justify-content-between">${starsHTML}</span>`;
}









/*____________________________________________SEARCH FUNCTIONALITY________________________________*/


$(document).ready(function () {
    $("#Searchbar").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#search-addon").click();
        }
    });
    $("#search-addon").click(function ()
    {
        if (document.querySelector('input[name="inlineRadioOptions"]:checked') !== null) {
            if(document.getElementById("Searchbar").value !== "") {
                let radioOption = document.querySelector('input[name="inlineRadioOptions"]:checked').value;
                let searchBarValue = document.getElementById("Searchbar").value;
                // Radio button is checked, access its value
                $.get('search_' + radioOption + '/' + searchBarValue, function (data, status) {
                    books = data;
                    displayBooks(filterList('checkBoxGenre'));
                });
                let searchInfo = document.getElementById("searchInfo");
                searchInfo.innerText="Searching all results matching: " + searchBarValue;
                searchInfo.classList.remove("invisible");
            }
            else{
                alert("Please specify what you want to search for")
            }
        } else {
            alert("Please select by what attribute you want to search the book")
        }
    });
});








/*________________________________________ORDER FUNCTION_____________________________________*/



/* ORDER BUTTON */
$("#OrderDropDown").change(function() {
    orderBooks();
    pageReset();
    currentPage=1;
    displayBooks(filteredBooks);
});


/* FUNCTIONALITY */
function orderBooks(){
    let orderItem= document.getElementById("OrderDropDown").value;

    if(orderItem==="name"){
        filteredBooks.sort((a, b) => {
            let fa = a.title.toLowerCase(),
                fb = b.title.toLowerCase();

            if (fa < fb) {
                return -1;
            }
            if (fa > fb) {
                return 1;
            }
            return 0;
        });
    }else if(orderItem==="date"){
        filteredBooks.sort((a, b) => {
            return a.year - b.year;
        });
    }else if(orderItem==="rating"){
        filteredBooks.sort((a, b) => {
            return b.averageRating - a.averageRating;
        });
    }else if(orderItem==="pageNumber"){
        filteredBooks.sort((a, b) => {
            return a.pageNumber - b.pageNumber;
        });
    }

}









/*__________________________________________FILTER FUNCTION__________________________________*/


/* FILTER BUTTON */
$('#filterButton').click(function(){
    displayBooks(filterList('checkBoxGenre'));
});


/* FILTER SPECIFICATIONS*/
function changePrice(val){
    document.getElementById("range").innerHTML= "$"+val;
}


/* FILTER */
function filterList(checkboxName){
    currentPage = 1;
    pageReset();
    let filteredBooksTemp;
    let dateRangeFrom= document.getElementById("dateFilterFrom").value;
    let dateRangeTo= document.getElementById("dateFilterTo").value;
    let priceRange= document.getElementById("priceSlider").value;
    let checkboxes= document.querySelectorAll('input[name="' + checkboxName + '"]:checked'),
        values = [];
    Array.prototype.forEach.call(checkboxes, function(el) {
        values.push(el.value);
    });
    if(values.length!=0){
        filteredBooksTemp= books.filter(books => values.includes(books.genre) );
    }
    else{
        filteredBooksTemp= books;
    }
    if (dateRangeFrom==""){
        dateRangeFrom= 0;
    }
    if (dateRangeTo==""){
        dateRangeTo= Infinity;
    }
    filteredBooksTemp= filteredBooksTemp.filter(filteredBooksTemp => filteredBooksTemp.price <= priceRange);
    filteredBooksTemp= filteredBooksTemp.filter(filteredBooksTemp =>filteredBooksTemp.year >= dateRangeFrom && filteredBooksTemp.year <=dateRangeTo);
    filteredBooks=filteredBooksTemp;
    orderBooks();
    return filteredBooksTemp;
}







/*______________________________________PAGINATION______________________________________*/

/* SAVE CURRENT PAGE */
$(document).ready(function () {
    let temp = document.querySelectorAll("#p-link-1, #p-link-2, #p-link-3");
    $(temp).click(function (){
        let page = parseInt(this.innerText);
    if (currentPage !== page) {
        currentPage = page;
        displayBooks(filteredBooks);
    }
    })
});


/*  CHANGE NUMBERS */
$(document).ready(function() {
    $("#nextPage").click(function () {
        if(document.getElementById("p-link-1").innerText == 1){
            document.getElementById("liPreviousPage").classList.remove("disabled");
        }
        let pageLinks = document.querySelectorAll("#p-link-1, #p-link-2, #p-link-3");
        pageLinks.forEach((link) => {
            let pageNumber = parseInt(link.innerText);
            link.innerText = pageNumber + 3;
            if (link.innerText == Math.ceil(filteredBooks.length/10)){
                document.getElementById("liNextPage").classList.add("disabled");
            }
            else if (link.innerText > Math.floor(filteredBooks.length/10)){
                link.style.visibility = "hidden";
            }
        });
    });
});


$(document).ready(function() {
    $("#previousPage").click(function () {
        let pLink1 = document.getElementById("p-link-1");
        let pageNumber = parseInt(pLink1.innerText);
        pLink1.innerText = pageNumber - 3;
        let pLink2 = document.getElementById("p-link-2");
        pLink2.innerText = parseInt(pLink1.innerText) + 1;
        pLink2.style.visibility = "visible";
        let pLink3 = document.getElementById("p-link-3");
        pLink3.innerText = parseInt(pLink1.innerText) + 2;
        pLink3.style.visibility = "visible";
        if(document.getElementById("p-link-1").innerText == 1){
            document.getElementById("liPreviousPage").classList.add("disabled");
        }
        document.getElementById("liNextPage").classList.remove("disabled");
    });
});


/* ENABLING/DISABLING PREVIOUS/NEXT BUTTON */
function enableDisable() {
    let pageLinks = document.querySelectorAll("#p-link-1, #p-link-2, #p-link-3");
    pageLinks.forEach((link) => {
        link.style.visibility= "visible";
        if (link.innerText == Math.ceil(filteredBooks.length/10)){
            document.getElementById("liNextPage").classList.add("disabled");
        }
        else if (link.innerText > Math.ceil(filteredBooks.length/10)){
            link.style.visibility = "hidden";
        }
    });
    if(document.getElementById("p-link-3").innerText < (filteredBooks.length/10) && document.getElementById("p-link-3").innerText !== ""){
        document.getElementById("liNextPage").classList.remove("disabled");
    }
    if(document.getElementById("p-link-1").innerText == 1) {
        document.getElementById("liPreviousPage").classList.add("disabled");
    }
}


/* PAGE 1 RESET*/
function pageReset(){
    let pLink1 = document.getElementById("p-link-1");
    pLink1.innerText = 1;
    let pLink2 = document.getElementById("p-link-2");
    pLink2.innerText = 2;
    let pLink3 = document.getElementById("p-link-3");
    pLink3.innerText = 3;
}