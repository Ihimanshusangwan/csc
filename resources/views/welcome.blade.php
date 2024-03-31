<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4K6EYYCJDR"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());

        gtag("config", "G-4K6EYYCJDR");
    </script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
    <title>Services</title>
    <style media="screen">
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .gradient {
            background: linear-gradient(90deg, #132247 0%, #b8b2b2 100%);
        }
    </style>
</head>

<body>
   
    <nav class="nav gradient flex flex-wrap items-center justify-between px-4 lap">
        <div class="flex flex-no-shrink items-center mr-6 py-3 text-grey-darkest">
            <span class="font-semibold text-3xl tracking-tight"><span class="text-white">E-Seva Kendra</span></span>
        </div>

        <input class="menu-btn hidden" type="checkbox" id="menu-btn" />
        <label class="menu-icon block cursor-pointer md:hidden px-2 py-4 relative select-none" for="menu-btn">
            <span class="navicon bg-grey-darkest flex items-center relative"></span>
        </label>

        <ul class="menu border-b md:border-none flex justify-end list-reset m-0 w-full md:w-auto">
            <li class="border-t md:border-none">
                <a href="index.html"
                    class="block md:inline-block px-4 py-3 no-underline text-grey-darkest hover:text-grey-darker text-white">Home</a>
            </li>
            <li class="border-t md:border-none">
                <a href="{{route('appointment');}}"
                    class="block md:inline-block px-4 py-3 no-underline text-grey-darkest hover:text-grey-darker text-white">Book an appointment</a>
            </li>

            <li class="border-t md:border-none dropdown inline-block relative">
                <a class="block md:inline-block px-4 py-3 no-underline text-grey-darkest hover:text-grey-darker"><span
                        class="mr-1 text-white">Login</span></a>
                <ul class="dropdown-menu absolute hidden text-gray-100 pt-1">
                    <li class="">
                        <a class="rounded-t bg-gray-900 hover:bg-gray-700 py-2 px-4 block whitespace-no-wrap text-white"
                            href="{{ route('admin.login') }}">Admin Login</a>
                    </li>
                    <li class="">
                        <a class="bg-gray-900 hover:bg-gray-700 py-2 px-4 block whitespace-no-wrap text-white"
                            href="{{ route('agent.login') }}">Agent Login</a>
                    </li>
                    <li class="">
                        <a class="bg-gray-900 hover:bg-gray-700 py-2 px-4 block whitespace-no-wrap text-white"
                            href="{{ route('staff.login') }}">Staff Login</a>
                    </li>
                    <li class="">
                        <a class="bg-gray-900 hover:bg-gray-700 py-2 px-4 block whitespace-no-wrap text-white"
                            href="software_d.html">Customer Login</a>
                    </li>
                    <li class="">
                        <a class="bg-gray-900 hover:bg-gray-700 py-2 px-4 block whitespace-no-wrap text-white"
                            href="digital.html">Register as a Customer</a>
                    </li>
                    <li class="">
                        <a class="bg-gray-900 hover:bg-gray-700 py-2 px-4 block whitespace-no-wrap text-white"
                            href="e_commerce_d.html">Register as a Agent</a>
                    </li>
                    <li class="">
                        <a class="bg-gray-900 hover:bg-gray-700 py-2 px-4 block whitespace-no-wrap text-white"
                            href="clg_project.html">Inquiry</a>
                    </li>
                </ul>
            </li>

            <li class="border-t md:border-none">
                <a href="about.html"
                    class="block md:inline-block px-4 py-3 no-underline text-grey-darkest hover:text-grey-darker text-white">About</a>
            </li>

            <li class="border-t md:border-none">
                <a href="contact.php"
                    class="block md:inline-block px-4 py-3 no-underline text-grey-darkest hover:text-grey-darker text-white">Contact</a>
            </li>
        </ul>
    </nav>
    <hr />
    <hr />
    <section class="text-gray-600 body-font bg-gray-50">
        <div class="heading-text-dream">
            <h1> ONE PLATFORM,ONE DREAM...</h1>
        </div>
        <div class="sub-dream-text">
            <h6>All your documents in a single click and at a safe place.</h6>
        </div>

    </section>
    <section class="text-gray-600 body-font bg-gray-50">
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-col text-center w-full mb-20">
                <h1 class="text-2xl font-medium title-font mb-4 text-green-900 underline">
                    Here is the Highlight of what you will get in your website
                </h1>
                <!-- <p class="lg:w-2/3 mx-auto leading-relaxed text-base">Whatever cardigan tote bag tumblr hexagon brooklyn asymmetrical gentrify, subway tile poke farm-to-table. Franzen you probably haven't heard of them.</p> -->
            </div>
            <div class="flex flex-wrap -m-4">
                <div class="p-4 lg:w-1/3 md:w-1/2 sm:w-1/1">
                    <div class="h-full flex flex-col items-center text-center">
                        <img alt="team"
                            class="flex-shrink-0 rounded-lg w-full object-scale-down h-20 object-cover object-center mb-4"
                            src="{{ asset('images/network.png') }}" />
                        <div class="w-full">
                            <h2 class="title-font font-medium text-xl text-gray-900">
                                Free Domain Registrations
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="p-4 lg:w-1/3 md:w-1/2 sm:w-1/1">
                    <div class="h-full flex flex-col items-center text-center">
                        <img alt="team"
                            class="flex-shrink-0 rounded-lg w-full object-scale-down h-20 object-cover object-center mb-4"
                            src="{{ asset('images/web-design.png') }}" />
                        <div class="w-full">
                            <h2 class="title-font font-medium text-xl text-gray-900">
                                Free Web Hosting
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="p-4 lg:w-1/3 md:w-1/2 sm:w-1/1">
                    <div class="h-full flex flex-col items-center text-center">
                        <img alt="team"
                            class="flex-shrink-0 rounded-lg w-full object-scale-down h-20 object-cover object-center mb-4"
                            src="{{ asset('images/laptop-screen.png') }}" />
                        <div class="w-full">
                            <h2 class="title-font font-medium text-xl text-gray-900">
                                Responsive Website Desktop, Mobile and Tab friendly
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="p-4 lg:w-1/3 md:w-1/2 sm:w-1/1">
                    <div class="h-full flex flex-col items-center text-center">
                        <img alt="team"
                            class="flex-shrink-0 rounded-lg w-full object-scale-down h-20 object-cover object-center mb-4"
                            src="{{ asset('images/facebook.png') }}" />
                        <div class="w-full">
                            <h2 class="title-font font-medium text-xl text-gray-900">
                                Facebook, Twitter, Linkedin, Instagram Integration
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="p-4 lg:w-1/3 md:w-1/2 sm:w-1/1">
                    <div class="h-full flex flex-col items-center text-center">
                        <img alt="team"
                            class="flex-shrink-0 rounded-lg w-full object-scale-down h-20 object-cover object-center mb-4"
                            src="{{ asset('images/lock.png') }}" />
                        <div class="w-full">
                            <h2 class="title-font font-medium text-xl text-gray-900">
                                Free SSL certificate
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="p-4 lg:w-1/3 md:w-1/2 sm:w-1/1">
                    <div class="h-full flex flex-col items-center text-center">
                        <img alt="team"
                            class="flex-shrink-0 rounded-lg w-full object-scale-down h-20 object-cover object-center mb-4"
                            src="{{ asset('images/server.png.png') }}" />
                        <div class="w-full">
                            <h2 class="title-font font-medium text-xl text-gray-900">
                                Unlimited MySQL Database
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="p-4 lg:w-1/3 md:w-1/2 sm:w-1/1">
                    <div class="h-full flex flex-col items-center text-center">
                        <img alt="team"
                            class="flex-shrink-0 rounded-lg w-full object-scale-down h-20 object-cover object-center mb-4"
                            src="{{ asset('images/repair-tool.png') }}" />
                        <div class="w-full">
                            <h2 class="title-font font-medium text-xl text-gray-900">
                                1 Year Free Maintenance
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="p-4 lg:w-1/3 md:w-1/2 sm:w-1/1">
                    <div class="h-full flex flex-col items-center text-center">
                        <img alt="team"
                            class="flex-shrink-0 rounded-lg w-full object-scale-down h-20 object-cover object-center mb-4"
                            src="{{ asset('images/linkedin.png') }}" />
                        <div class="w-full">
                            <h2 class="title-font font-medium text-xl text-gray-900">
                                Free Linkedin Profile
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="p-4 lg:w-1/3 md:w-1/2 sm:w-1/1">
                    <div class="h-full flex flex-col items-center text-center">
                        <img alt="team"
                            class="flex-shrink-0 rounded-lg w-full object-scale-down h-20 object-cover object-center mb-4"
                            src="{{ asset('images/support.png') }}" />
                        <div class="w-full">
                            <h2 class="title-font font-medium text-xl text-gray-900">
                                24/7/365 Support
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr />

    <section class="text-gray-600 body-font gradient">
        <div class="container px-5 py-24 mx-auto justify-center flex sm:flex-nowrap flex-wrap">
            <div
                class="lg:w-1/1 md:w-1/1 bg-gray-300 rounded-lg overflow-hidden sm:mr-10 p-10 flex items-end justify-start relative">
                <iframe width="100%" height="100%" class="absolute inset-0" frameborder="0" title="map" marginheight="0"
                    marginwidth="0" scrolling="no"
                    src="https://maps.google.com/maps?width=100%&amp;height=600&amp;hl=en&amp;q=(Pune%20maharashtra)&amp;ie=UTF8&amp;t=&amp;z=14&amp;iwloc=B&amp;output=embed"
                    style="filter: grayscale(1) contrast(1.2) opacity(0.4)"></iframe>
                <div class="bg-white relative flex flex-wrap py-6 rounded shadow-md">
                    <div class="lg:w-1/2 px-6">
                        <h2 class="title-font font-semibold text-gray-900 tracking-widest text-md font-bold">
                            For More Information Contact Us Now
                        </h2>
                        <!-- <p class="mt-1">Pune Maharashtra</p> -->
                        <button
                            class="mx-auto mt-12 text-white bg-gray-900 border-0 py-2 px-8 focus:outline-none hover:bg-blue-900 rounded-2xl text-sm">
                            <a href="contact.php">Contact Now</a>
                        </button>
                    </div>
                    <div class="lg:w-1/1 px-6 mt-4 lg:mt-0">
                        <h2 class="title-font font-semibold text-gray-900 tracking-widest text-sm font-bold">
                            EMAIL
                        </h2>
                        <a class="text-indigo-500 leading-relaxed">info@webiflysolutions.com</a>
                        <h2 class="title-font font-semibold text-gray-900 tracking-widest text-sm font-bold mt-4">
                            PHONE
                        </h2>
                        <p class="leading-relaxed">7447753759</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <footer class="text-gray-900 body-font bg-black">
        <div
            class="container px-5 py-24 mx-auto flex md:items-center lg:items-start md:flex-row md:flex-nowrap flex-wrap flex-col">
            <div class="flex-grow flex flex-wrap md:pr-20 -mb-10 md:text-left text-center order-first">
                <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                    <h2 class="title-font font-medium text-gray-100 tracking-widest text-md mb-3">
                        Webifly Solutions
                    </h2>
                    <hr />
                    <br />
                    <nav class="list-none mb-10">
                        <li>
                            <a class="text-gray-100 hover:text-gray-100">Developer Team</a>
                        </li>
                    </nav>
                </div>
                <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                    <h2 class="title-font font-medium text-gray-100 tracking-widest text-md mb-3">
                        Quick Links
                    </h2>
                    <hr />
                    <br />
                    <nav class="list-none mb-10">
                        <li>
                            <a href="web_d.html" class="text-yellow-700 hover:text-yellow-500">Website Design</a>
                        </li>
                        <li>
                            <a href="software_d.html" class="text-yellow-700 hover:text-yellow-500">ERP/CRM Software
                                Development</a>
                        </li>
                        <li>
                            <a href="android.html" class="text-yellow-700 hover:text-yellow-500">Android App
                                Development</a>
                        </li>
                        <li>
                            <a href="digital.html" class="text-yellow-700 hover:text-yellow-500">Digital Marketing</a>
                        </li>
                        <li>
                            <a href="" class="text-yellow-700 hover:text-yellow-500">SEO</a>
                        </li>
                        <li>
                            <a href="e_commerce_d.html" class="text-yellow-700 hover:text-yellow-500">Ecommerce Web
                                Design</a>
                        </li>
                        <li>
                            <a href="clg_project.html" class="text-yellow-700 hover:text-yellow-500">College
                                Projects</a>
                        </li>
                    </nav>
                </div>
                <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                    <h2 class="title-font font-medium text-gray-100 tracking-widest text-md mb-3">
                        Get Started
                    </h2>
                    <hr />
                    <br />
                    <nav class="list-none mb-10">
                        <li>
                            <a class="text-gray-100 hover:text-gray-300">Get nitros to your full site business</a>
                        </li>
                        <li>
                            <a class="text-gray-100 hover:text-gray-300">Get a quote</a>
                        </li>
                    </nav>
                </div>

                <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                    <h2 class="title-font font-medium text-gray-100 tracking-widest text-md mb-3">
                        Contact Us
                    </h2>
                    <hr />
                    <br />
                    <nav class="list-none mb-10">
                        <li>
                            <a class="text-gray-100 hover:text-gray-300"> +91 7447753759</a>
                        </li>
                        <li>
                            <a class="text-blue-400 hover:text-gray-300 underline">info@webiflysolutions.com</a>
                        </li>
                    </nav>
                </div>
            </div>
        </div>
        <hr />
        <div class="bg-black">
            <div class="container mx-auto py-4 px-5 flex flex-wrap flex-col sm:flex-row">
                <p class="text-gray-500 text-sm text-center sm:text-left">
                    Copyright © 2021 Webifly Solutions —
                    <a href="/" rel="noopener noreferrer" class="text-gray-600 ml-1"
                        target="_blank">@webiflysolutions</a>
                </p>
                <span class="inline-flex sm:ml-auto sm:mt-0 mt-2 justify-center sm:justify-start">
                    <a href="" class="text-gray-500">
                        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                        </svg>
                    </a>
                    <a href="" class="ml-3 text-gray-500">
                        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            class="w-5 h-5" viewBox="0 0 24 24">
                            <path
                                d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z">
                            </path>
                        </svg>
                    </a>
                    <a href="" class="ml-3 text-gray-500">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                        </svg>
                    </a>
                    <a href="" class="ml-3 text-gray-500">
                        <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="0" class="w-5 h-5" viewBox="0 0 24 24">
                            <path stroke="none"
                                d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z">
                            </path>
                            <circle cx="4" cy="4" r="2" stroke="none"></circle>
                        </svg>
                    </a>
                </span>
            </div>
        </div>
    </footer>
</body>

</html>