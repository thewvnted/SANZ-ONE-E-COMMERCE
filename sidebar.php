<div class="col-md-2">
<!--sidenav -->
<style>
        body {
            font-family: "Source Sans Pro", sans-serif;
            font-size: 100%;
            overflow-y: scroll;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            background-color: #fefefe;
        }

        .app {
            height: 100vh;
        }

        .sidebar {
            position: absolute;
            width: 12em;
            padding: 0.5em 0;
            height: calc(200% - 50%);
            top: 26%;
            bottom: 100%
            right: -12em;
            overflow: hidden;
            background-color: #19222a;
            -webkit-transform: translateZ(0);
            visibility: visible;
            -webkit-backface-visibility: hidden;

            header {
                background-color: #09f;
                width: 100%;
                display: block;
                padding: 0.75em 1em;
            }
        }

        .sidebar-nav {
            position: fixed;
            background-color: #19222a;
            height: 100%;
            font-weight: 400;
            font-size: 1.2em;
            overflow: auto;
            padding-bottom: 6em;
            z-index: 9;
            overflow: hidden;
            -webkit-overflow-scrolling: touch;

            ul {
                list-style: none;
                display: block;
                padding: 0;
                margin: 0;

                li {
                    margin-left: 0;
                    padding-left: 0;
                    display: inline-block;
                    width: 100%;

                    a {
                        color: rgba(255, 255, 255, 0.9);
                        font-size: 0.75em;
                        padding: 1.05em 1em;
                        position: relative;
                        display: block;

                        &:hover {
                            background-color: rgba(0, 0, 0, 0.9);
                            transition: all 0.6s ease;
                        }
                    }

                    i {
                        font-size: 1.8em;
                        padding-right: 0.5em;
                        width: 9em;
                        display: inline;
                        vertical-align: middle;
                    }
                }
            }

            /* Chev elements */
            & > ul > li > a:after {
                content: '\f125';
                font-family: ionicons;
                font-size: 0.5em;
                width: 10px;
                color: #fff;
                position: absolute;
                right: 0.75em;
                top: 45%;
            }

            /* Nav-Flyout */
            & .nav-flyout {
                position: absolute;
                background-color: #080D11;
                z-index: 9;
                left: 2.5em;
                top: 0;
                height: 100vh;
                transform: translateX(100%);
                transition: all 0.5s ease;

                a:hover {
                    background-color: rgba(255, 255, 255, 0.05);
                }
            }

            /* Hover */
            & ul > li:hover {
                .nav-flyout {
                    transform: translateX(0);
                    transition: all 0.5s ease;
                }
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <section class="app">
                    <aside class="sidebar">
                        <header>
                            Menu
                        </header>
                        <nav class="sidebar-nav">
                            <ul>
                                <li>
                                    <a href="#"><i class="ion-bag"></i> <span>Brand</span></a>
                                    <ul class="nav-flyout">
                                        <li>
                                            <a href="#"><i class="ion-ios-color-filter-outline"></i>PlayStation</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="ion-ios-clock-outline"></i>Switch</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="ion-android-star-outline"></i>XBOX</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><i class="ion-bag"></i> <span>Categories</span></a>
                                    <ul class="nav-flyout">
                                        <li>
                                            <a href="#"><i class="ion-ios-alarm-outline"></i>Console</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="ion-ios-camera-outline"></i>Gaming Pads</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="ion-ios-chatboxes-outline"></i>Games</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </aside>
                </section>
            </div>
        </div>
    </div>

