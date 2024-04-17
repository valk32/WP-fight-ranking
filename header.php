<head>
    <meta http-equiv="Content-Language" content="ja" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>応援サイト</title>
</head>

<body class="">
    <nav
        class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/fight-ranking" class="px-4 flex items-center space-x-3">
                <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">夢の対戦</span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <button data-collapse-toggle="navbar-sticky" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul
                    class="flex flex-col p-4 md:p-0 mt-4 font-medium border rounded-lg md:space-x-8 md:flex-row md:mt-0 md:border-0 border-gray-700">
                    <li>
                        <button id="dropdownNavbarLink1" data-dropdown-toggle="dropdownNavbar1"
                            class="flex items-center justify-between w-full py-2 px-3 rounded md:border-0 md:p-0 md:w-auto text-white md:hover:text-blue-500 focus:text-white border-gray-700 hover:bg-gray-700 md:hover:bg-transparent"
                            aria-current="page">
                            夢の対戦カード
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                        <div id="dropdownNavbar1"
                            class="z-10 hidden font-normal divide-y rounded-lg shadow w-44 bg-gray-800 divide-gray-600">
                            <ul class="py-2 text-sm text-gray-200" aria-labelledby="dropdownLargeButton">
                                <?php
                                $args = array(
                                    'post_type' => 'orgtype',
                                    'posts_per_page' => -1, // Number of posts to retrieve
                                    'order' => 'ASC',
                                );

                                $custom_query = new WP_Query($args);
                                if ($custom_query->have_posts()) {
                                    while ($custom_query->have_posts()) {
                                        $custom_query->the_post();
                                        $orgID = get_the_ID();
                                        ?>

                                        <li>
                                            <a href="/fight-ranking/requestcard?orgt=<?php echo $orgID ?>"
                                                class="block px-4 py-2 hover:bg-gray-600 hover:text-white">
                                                <?php echo get_post_meta($orgID, 'orgname', true) ?>
                                            </a>
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                    </li>
                    <!-- <li> <a href="/fight-ranking/prediction"
                            class="block py-2 px-3 rounded md:p-0 md:hover:text-blue-500 text-white hover:bg-gray-700 hover:text-white md:hover:bg-transparent border-gray-700">
                            勝敗予想 </a>
                    </li> -->


                    <li>
                        <button id="dropdownNavbarLink0" data-dropdown-toggle="dropdownNavbar0"
                            class="flex items-center justify-between w-full py-2 px-3 rounded md:border-0 md:p-0 md:w-auto text-white md:hover:text-blue-500 focus:text-white border-gray-700 hover:bg-gray-700 md:hover:bg-transparent"
                            aria-current="page">
                            勝敗予想    
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                        <div id="dropdownNavbar0"
                            class="z-10 hidden font-normal divide-y rounded-lg shadow w-auto bg-gray-800 divide-gray-600">
                            <ul class="py-2 text-sm text-gray-200" aria-labelledby="dropdownLargeButton">
                                <?php
                                $current_date = date('Y-m-d');
                                $args = array(
                                    'post_type' => 'competition',
                                    'posts_per_page' => -1, // Number of posts to retrieve
                                    'orderby' => 'meta_value',
                                    'order' => 'ASC',
                                    'meta_key' => 'date',
                                    'meta_query' => array( // Correctly structure the meta_query
                                        array(
                                            'key' => 'date',
                                            'value' => $current_date,
                                            'compare' => '>=',
                                            'type' => 'DATE',
                                        ),
                                    ),
                                );

                                $custom_query = new WP_Query($args);
                                if ($custom_query->have_posts()) {
                                    while ($custom_query->have_posts()) {
                                        $custom_query->the_post();
                                        $competitionID = get_the_ID();
                                        $competition_name = get_post_meta($competitionID, 'competition_name', true);
                                        $date = get_post_meta($competitionID, 'date', true);
                                        ?>
                                        <li class="block px-4 py-2 hover:bg-gray-600 hover:text-white">
                                            <a <?php echo 'href="/fight-ranking/prediction/?id='.$competitionID.'"' ?>
                                                class="flex items-center justify-between w-full py-2 px-3 rounded md:border-0 md:p-0 md:w-auto cursor-pointer text-white md:hover:text-blue-500 focus:text-white border-gray-700 hover:bg-gray-700 md:hover:bg-transparent"
                                                aria-current="page">
                                                <?php echo $competition_name ?> 「<?php  echo (new DateTime($date))->format("Y-m-d"); ?>」
                                            </a>
                                        </li>
                                    <?php }
                                    wp_reset_postdata();
                                }
                                ?>
                            </ul>
                        </div>
                    </li>


                    <li>
                        <a href="/fight-ranking/rank?favorite=1"
                            class="block py-2 px-3 rounded md:p-0 md:hover:text-blue-500 text-white hover:bg-gray-700 hover:text-white md:hover:bg-transparent border-gray-700">
                            人気選手ランキング
                        </a>
                    </li>
                    <li>
                        <button id="dropdownNavbarLink2" data-dropdown-toggle="dropdownNavbar2"
                            class="flex items-center justify-between w-full py-2 px-3 rounded md:border-0 md:p-0 md:w-auto text-white md:hover:text-blue-500 focus:text-white border-gray-700 hover:bg-gray-700 md:hover:bg-transparent"
                            aria-current="page">
                            階級別ランキング
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                        <div id="dropdownNavbar2"
                            class="z-10 hidden font-normal divide-y rounded-lg shadow w-auto bg-gray-800 divide-gray-600">
                            <ul class="py-2 text-sm text-gray-200" aria-labelledby="dropdownLargeButton">
                                <?php
                                $args = array(
                                    'post_type' => 'orgtype',
                                    'posts_per_page' => -1, // Number of posts to retrieve
                                    'order' => 'ASC',
                                );

                                $custom_query = new WP_Query($args);
                                if ($custom_query->have_posts()) {
                                    while ($custom_query->have_posts()) {
                                        $custom_query->the_post();
                                        $orgID = get_the_ID();
                                        $weighttypes = get_post_meta($orgID, 'org_weighttype', true);
                                        ?>
                                        <li class="block px-4 py-2 hover:bg-gray-600 hover:text-white">
                                            <a id="dropdownNavbarLink<?php echo $orgID ?>" <?php if(!$weighttypes) echo 'href="/fight-ranking/rank/?ranktype='.$orgID.'"' ?>
                                                data-dropdown-toggle="dropdownNavbar<?php echo $orgID ?>"
                                                class="flex items-center justify-between w-full py-2 px-3 rounded md:border-0 md:p-0 md:w-auto cursor-pointer text-white md:hover:text-blue-500 focus:text-white border-gray-700 hover:bg-gray-700 md:hover:bg-transparent"
                                                aria-current="page">
                                                <?php echo get_post_meta($orgID, 'orgname', true) ?>
                                                <?php if($weighttypes) echo '<i class="fa fa-angle-down" aria-hidden="true">' ?>
                                                </i>
                                            </a>
                                            <?php
                                            if ($weighttypes) {
                                                ?>
                                            <div id="dropdownNavbar<?php echo $orgID ?>"
                                                class="z-10 hidden font-normal divide-y rounded-lg shadow w-44 bg-gray-800 divide-gray-600">
                                                <ul class="py-2 text-sm text-gray-200" aria-labelledby="dropdownLargeButton">
                                                    <?php
                                                        foreach ($weighttypes as $key => $weightID) {
                                                            ?>
                                                            <li>
                                                                <a href="/fight-ranking/rank/?ranktype=<?php echo $orgID ?>&weightt=<?php echo $weightID ?>"
                                                                    class="block px-4 py-2 hover:bg-gray-600 hover:text-white">
                                                                    <?php echo get_post_meta($weightID, 'weightname', true) ?>
                                                                </a>
                                                            <?php }
                                                    ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <?php } ?>
                                        </li>
                                    <?php }
                                    wp_reset_postdata();
                                }
                                ?>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <main class="px-4 py-28 bg-auto bg-cover bg-center min-h-[92vh] " style="background-image: url('')">
        <div class="relative w-auto max-w-[700px] mx-auto text-[2.4vw] sm:text-base">