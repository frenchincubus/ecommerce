let quantity = document.getElementById('quantity');
let submit = document.getElementById('submit');
let product = document.getElementById('title');
let total = document.getElementById('total');
let cart = document.getElementById('cart');
let cartcount = document.getElementById('cartcount');

var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

if(submit !== null) 
    submit.onclick = async function(e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append("quantity", quantity.value);
        formData.append("submit", "submit");
        console.log(formData);
        await axios.post('../../category_products/product/'+product.getAttribute('product'), formData).then(
            res => {
                console.log(res.data.cartProducts);
                if(cart.hasChildNodes()) {
                    while(cart.firstChild) {
                        cart.removeChild(cart.lastChild);
                    }
                }
                for(const cartProduct of res.data.cartProducts) {
                    let div = document.createElement('div');
                    let innerDiv = document.createElement('div');
                    let aDiv = document.createElement('a');
                    aDiv.href = baseUrl + '/category_products/product/' + cartProduct.product.id;
                    aDiv.className = 'd-inline';
                    aDiv.textContent = cartProduct.product.name;
                    innerDiv.append(aDiv);
                    let span1 = document.createElement('span');
                    span1.className = 'd-inline';
                    span1.innerHTML = 'x ' + cartProduct.quantity + ' ';
                    let span2 = document.createElement('span');
                    span2.className = 'd-inline text-bold';
                    span2.innerHTML = parseFloat(cartProduct.product.ttcPrice)*cartProduct.quantity + ' €';
                    let span3 = document.createElement('span');
                    span3.className = 'd-inline float-right';
                    let aSpan = document.createElement('a');
                    aSpan.href = baseUrl + '/cart/remove_product/' + cartProduct.id;
                    aSpan.className = 'material-icons';
                    aSpan.textContent = 'delete';
                    span3.append(aSpan);
                    div.append(innerDiv);
                    div.append(span1);
                    div.append(span2);
                    div.append(span3);
                    let divider = document.createElement('div');
                    divider.className = 'dropdown-divider';
                    cart.append(div);
                    cart.append(divider);
                }
                

                total.innerHTML = 'Total: '+res.data.totalPrice+ ' €';

                cartcount.textContent = res.data.cartProducts.length;
            }
        );
    }