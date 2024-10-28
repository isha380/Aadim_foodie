<?php

session_start();
if (!isset($_SESSION['roll'])) {
    header("Location:login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="../assets/css/component.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="menu-body">

    <div class="menu-headline">

        <div class="navBar-banner-headings menu">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About us</a></li>
                <li><a href="#">Contact</a></li>

            </ul>
        </div>
        <div class="menu-headline-title">
            <span>
                <div class="marquee">Order Your Food Now!!</div>
            </span>
        </div>
    </div>
    <h2>
        welcome
    </h2>
    <div class="menu-banner">
        <div class="menu-image">
            <img src="../assets/image/icon/Hungry.png">
        </div>
        <div class="menu-info">
            <span class="menu-info-txt">Grab yourself some tasty FOOD</span>
            <p></p>
        </div>
    </div>
    <div class="menu-container-headline">
        <span class="menu-container-txt">MENU</span>
    </div>

    <div class="menu-container">


        <button class=" arrow menu pre"><svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 1024 1024">
                <path fill=" #543787" d="M685.248 104.704a64 64 0 0 1 0 90.496L368.448 512l316.8 316.8a64 64 0 0 1-90.496 90.496L232.704 557.248a64 64 0 0 1 0-90.496l362.048-362.048a64 64 0 0 1 90.496 0" />
            </svg></button>


        <button class="arrow menu next"><svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 1024 1024">
                <path fill="#543787" d="M338.752 104.704a64 64 0 0 0 0 90.496l316.8 316.8l-316.8 316.8a64 64 0 0 0 90.496 90.496l362.048-362.048a64 64 0 0 0 0-90.496L429.248 104.704a64 64 0 0 0-90.496 0" />
            </svg></button>


        <div class="dish-slide-container menu">
            <div class="dish-content-wrapper menu">
                <div class="dish-image">
                    <img src="../assets/image/web_page/samosa.jpg">
                </div>
                <div class="dish-info">
                    <div class="dish-name">
                        <button class="btn dish">Samosa</button>
                    </div>
                    <div class="dish-price">
                        <span class="slider-dish-price">Per Piece:<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3m0 0c6.667 0 6.667-10 0-10" />
                            </svg>25</span>
                    </div>
                    <div class="dish-status-wrapper">
                        <span class="dish-status-text">Status:<button class="dish-status-label" id="dish-status">available</button></span>

                    </div>
                    <div class="dish-quantity-wrapper">
                        <button class="btn-quantity decrement" onclick="decrementQuantity('dish-quantity-1')">-</button>
                        <div class="dish-number" id="dish-quantity-1">1</div>
                        <button class="btn-quantity increment" onclick="incrementQuantity('dish-quantity-1')">+</button>

                    </div>
                    <div class="add-to-cart-button">
                        <button class="btn cart-btn"><span class="cart-btn-text">Add to cart</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill=" #feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                            </svg>
                        </button>
                    </div>
                </div>



            </div>
            <div class="dish-content-wrapper menu">
                <div class="dish-image">
                    <img src="../assets/image/web_page/pakoda.png">
                </div>
                <div class="dish-info">
                    <div class="dish-name">
                        <button class="btn dish">Pakoda</button>
                    </div>
                    <div class="dish-price">
                        <span class="slider-dish-price">Per Plate:<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3m0 0c6.667 0 6.667-10 0-10" />
                            </svg>50</span>
                    </div>
                    <div class="dish-status-wrapper">
                        <span class="dish-status-text">Status:<button class="dish-status-label" id="dish-status">available</button></span>

                    </div>
                    <div class="dish-quantity-wrapper">
                        <button class="btn-quantity decrement" onclick="decrementQuantity('dish-quantity-1')">-</button>
                        <div class="dish-number" id="dish-quantity-1">1</div>
                        <button class="btn-quantity increment" onclick="incrementQuantity('dish-quantity-1')">+</button>

                    </div>
                    <div class="add-to-cart-button">
                        <button class="btn cart-btn"><span class="cart-btn-text">Add to cart</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill=" #feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                            </svg>
                        </button>
                    </div>
                </div>



            </div>
            <div class="dish-content-wrapper menu">
                <div class="dish-image">
                    <img src="../assets/image/web_page/chicken-jhol-momo.jpg">
                </div>
                <div class="dish-info">
                    <div class="dish-name">
                        <button class="btn dish">Chicken Mo:Mo</button>
                    </div>
                    <div class="dish-price">
                        <span class="slider-dish-price">Per Plate:<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3m0 0c6.667 0 6.667-10 0-10" />
                            </svg>90</span>
                    </div>
                    <div class="dish-status-wrapper">
                        <span class="dish-status-text">Status:<button class="dish-status-label" id="dish-status">available</button></span>

                    </div>
                    <div class="dish-quantity-wrapper">
                        <button class="btn-quantity decrement" onclick="decrementQuantity('dish-quantity-1')">-</button>
                        <div class="dish-number" id="dish-quantity-1">1</div>
                        <button class="btn-quantity increment" onclick="incrementQuantity('dish-quantity-1')">+</button>

                    </div>
                    <div class="add-to-cart-button">
                        <button class="btn cart-btn"><span class="cart-btn-text">Add to cart</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill=" #feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                            </svg>
                        </button>
                    </div>
                </div>



            </div>
            <div class="dish-content-wrapper menu">
                <div class="dish-image">
                    <img src="../assets/image/web_page/coffee2.jpg">
                </div>
                <div class="dish-info">
                    <div class="dish-name">
                        <button class="btn dish">Coffee</button>
                    </div>
                    <div class="dish-price">
                        <span class="slider-dish-price">Per Plate:<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3m0 0c6.667 0 6.667-10 0-10" />
                            </svg>45</span>
                    </div>
                    <div class="dish-status-wrapper">
                        <span class="dish-status-text">Status:<button class="dish-status-label" id="dish-status">available</button></span>

                    </div>
                    <div class="dish-quantity-wrapper">
                        <button class="btn-quantity decrement" onclick="decrementQuantity('dish-quantity-1')">-</button>
                        <div class="dish-number" id="dish-quantity-1">1</div>
                        <button class="btn-quantity increment" onclick="incrementQuantity('dish-quantity-1')">+</button>

                    </div>
                    <div class="add-to-cart-button">
                        <button class="btn cart-btn"><span class="cart-btn-text">Add to cart</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill=" #feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                            </svg>
                        </button>
                    </div>
                </div>



            </div>
            <div class="dish-content-wrapper menu">
                <div class="dish-image">
                    <img src="../assets/image/web_page/sadeko-momo.jpg">
                </div>
                <div class="dish-info">
                    <div class="dish-name">
                        <button class="btn dish">Sadeko-MoMo</button>
                    </div>
                    <div class="dish-price">
                        <span class="slider-dish-price">Per Plate:<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3m0 0c6.667 0 6.667-10 0-10" />
                            </svg>150</span>
                    </div>
                    <div class="dish-status-wrapper">
                        <span class="dish-status-text">Status:<button class="dish-status-label" id="dish-status">available</button></span>

                    </div>
                    <div class="dish-quantity-wrapper">
                        <button class="btn-quantity decrement" onclick="decrementQuantity('dish-quantity-1')">-</button>
                        <div class="dish-number" id="dish-quantity-1">1</div>
                        <button class="btn-quantity increment" onclick="incrementQuantity('dish-quantity-1')">+</button>

                    </div>
                    <div class="add-to-cart-button">
                        <button class="btn cart-btn"><span class="cart-btn-text">Add to cart</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill=" #feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                            </svg>
                        </button>
                    </div>
                </div>



            </div>
            <div class="dish-content-wrapper menu">
                <div class="dish-image">
                    <img src="../assets/image/web_page/sadeko-momo.jpg">
                </div>
                <div class="dish-info">
                    <div class="dish-name">
                        <button class="btn dish">Sadeko-MoMo</button>
                    </div>
                    <div class="dish-price">
                        <span class="slider-dish-price">Per Plate:<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3m0 0c6.667 0 6.667-10 0-10" />
                            </svg>150</span>
                    </div>
                    <div class="dish-status-wrapper">
                        <span class="dish-status-text">Status:<button class="dish-status-label" id="dish-status">available</button></span>

                    </div>
                    <div class="dish-quantity-wrapper">
                        <button class="btn-quantity decrement" onclick="decrementQuantity('dish-quantity-1')">-</button>
                        <div class="dish-number" id="dish-quantity-1">1</div>
                        <button class="btn-quantity increment" onclick="incrementQuantity('dish-quantity-1')">+</button>

                    </div>
                    <div class="add-to-cart-button">
                        <button class="btn cart-btn"><span class="cart-btn-text">Add to cart</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill=" #feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                            </svg>
                        </button>
                    </div>
                </div>



            </div>
            <div class="dish-content-wrapper menu">
                <div class="dish-image">
                    <img src="../assets/image/web_page/dal bhatt.png">
                </div>
                <div class="dish-info">
                    <div class="dish-name">
                        <button class="btn dish">Dal Bhatt</button>
                    </div>
                    <div class="dish-price">
                        <span class="slider-dish-price">Per Plate:<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3m0 0c6.667 0 6.667-10 0-10" />
                            </svg>100</span>
                    </div>
                    <div class="dish-status-wrapper">
                        <span class="dish-status-text">Status:<button class="dish-status-label" id="dish-status">available</button></span>

                    </div>
                    <div class="dish-quantity-wrapper">
                        <button class="btn-quantity decrement" onclick="decrementQuantity('dish-quantity-1')">-</button>
                        <div class="dish-number" id="dish-quantity-1">1</div>
                        <button class="btn-quantity increment" onclick="incrementQuantity('dish-quantity-1')">+</button>

                    </div>
                    <div class="add-to-cart-button">
                        <button class="btn cart-btn"><span class="cart-btn-text">Add to cart</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill=" #feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                            </svg>
                        </button>
                    </div>
                </div>



            </div>
            <div class="dish-content-wrapper menu">
                <div class="dish-image">
                    <img src="../assets/image/web_page/sadeko-momo.jpg">
                </div>
                <div class="dish-info">
                    <div class="dish-name">
                        <button class="btn dish">Sadeko-MoMo</button>
                    </div>
                    <div class="dish-price">
                        <span class="slider-dish-price">Per Plate:<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3m0 0c6.667 0 6.667-10 0-10" />
                            </svg>150</span>
                    </div>
                    <div class="dish-status-wrapper">
                        <span class="dish-status-text">Status:<button class="dish-status-label" id="dish-status">available</button></span>

                    </div>
                    <div class="dish-quantity-wrapper">
                        <button class="btn-quantity decrement" onclick="decrementQuantity('dish-quantity-2')">-</button>
                        <div class="dish-number" id="dish-quantity-2">1</div>
                        <button class="btn-quantity increment" onclick="incrementQuantity('dish-quantity-2')">+</button>

                    </div>
                    <div class="add-to-cart-button">
                        <button class="btn cart-btn"><span class="cart-btn-text">Add to cart</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill=" #feb737" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2" />
                            </svg>
                        </button>
                    </div>
                </div>



            </div>

        </div>
    </div>


    <div class="total-order-wrapper">
        <div class="your-order-container">
            <span class="your-order-txt">Your Orders</span>
        </div>
        <div class="order-wrapper">
            <div class="order">
                <div class="order-name"></div>
                <div class="order-manipulate">
                    <button class="decrement">-</button>
                    <button class="increment">+</button>
                    <button class="delete"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6zM19 4h-3.5l-1-1h-5l-1 1H5v2h14z" />
                        </svg></button>
                </div>
            </div>

        </div>
    </div>
    <div class="order-details">
        <button class="view-order-details">View order details</button>
    </div>



    <script src="./js/slide.js">

    </script>
    <script>
        function incrementQuantity(id) {
            const quantityElem = document.getElementById(id);
            let quantity = parseInt(quantityElem.textContent);
            quantityElem.textContent = quantity + 1;
        }

        function decrementQuantity(id) {
            const quantityElem = document.getElementById(id);
            let quantity = parseInt(quantityElem.textContent);
            if (quantity > 1) {
                quantityElem.textContent = quantity - 1;
            }
        }
    </script>
</body>

</html>