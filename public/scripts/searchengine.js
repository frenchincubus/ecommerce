let searchField = document.getElementById("search");
        let div = document.createElement("div"); div.className = "dropdown";
        div.style.marginTop = "40px";
        // div.style.marginRight = "100px";
        let liste = document.createElement("div"); liste.className = "dropdown-menu show";
        div.appendChild(liste);
        searchField.oninput = function() {
            let url = '/products/search/'+searchField.value;
            if (searchField.value.length > 1 ) {
                axios.get(url)
                .then(function(res) {
                    //console.log(res);
                    if(liste.hasChildNodes()) {
                        while(liste.firstChild) {
                            liste.removeChild(liste.lastChild);
                        }
                    }
                    if (res.data.length > 0){
                        res.data.map(val => {
                            /* block elements creation 
                            * <div>
                            *    <a>product name + price</a>
                            *    dans catégorie 
                            *    <a>product 1st category</a>
                            * </div>
                            * <div>Divider</div>
                            */
                            divItem = document.createElement("div"); 
                            li = document.createElement("a");
                            cat = document.createElement("a");
                            text = document.createElement("span");
                            divider = document.createElement("div");
                            
                            // tags attribute fills
                            divItem.className ="dropdown-item";
                            li.href = '/category_products/product/'+val.id;
                            li.innerHTML = val.name + ' - ' + val.ttcPrice + '€';                            
                            cat.href = "/category_products/" + val.categories[0].id;                            
                            cat.innerHTML = val.categories[0].name;
                            text.innerText = " dans catégorie: ";
                            divider.className = "dropdown-divider";
                            
                            // block mounting
                            divItem.appendChild(li);
                            divItem.appendChild(text);
                            divItem.appendChild(cat);
                            liste.appendChild(divItem);                            
                            liste.appendChild(divider);
                        });
                        searchField.before(div);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });          
            } else if(searchField.value.length == 0) {
                searchField.parentNode.removeChild(div);
            }
        }