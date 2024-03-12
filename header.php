<head>
    <meta http-equiv="Content-Language" content="ja" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>応援サイト</title>
</head>

<body>
    <nav
        class="bg-gray-900 h-28 flex flex-col justify-end bg-cover fixed w-full z-20 top-0 start-0 border-b border-gray-600">
        <div class="max-w-screen-xl flex w-full flex-wrap items-center justify-between mx-auto p-4">
            <a href="/fight-ranking" class="px-4 flex items-center space-x-3">
                <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">夢の対戦</span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-0">
                <button data-collapse-toggle="navbar-sticky" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm rounded-lg md:hidden focus:outline-none focus:ring-2 text-gray-400 hover:bg-gray-700 focus:ring-gray-600"
                    aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only"></span>
                    <i class="fa fa-bars text-3xl"></i>
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
                                                <?php echo get_post_meta($orgID, 'orgname', true) ?>編
                                            </a>
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                    </li>
                    <li> <a href="/fight-ranking/prediction"
                            class="block py-2 px-3 rounded md:p-0 md:hover:text-blue-500 text-white hover:bg-gray-700 hover:text-white md:hover:bg-transparent border-gray-700">
                            勝敗予想 </a>
                    </li>
                    <li>
                        <a href="/fight-ranking/rank?favorite=1"
                            class="block py-2 px-3 rounded md:p-0 md:hover:text-blue-500 text-white hover:bg-gray-700 hover:text-white md:hover:bg-transparent border-gray-700">
                            人気総選挙
                        </a>
                    </li>
                    <li>
                        <button id="dropdownNavbarLink2" data-dropdown-toggle="dropdownNavbar2"
                            class="flex items-center justify-between w-full py-2 px-3 rounded md:border-0 md:p-0 md:w-auto text-white md:hover:text-blue-500 focus:text-white border-gray-700 hover:bg-gray-700 md:hover:bg-transparent"
                            aria-current="page">
                            総選挙
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>
                        <div id="dropdownNavbar2"
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
                                        <li class="block px-4 py-2 hover:bg-gray-600 hover:text-white">
                                            <button id="dropdownNavbarLink<?php echo $orgID ?>"
                                                data-dropdown-toggle="dropdownNavbar<?php echo $orgID ?>"
                                                class="flex items-center justify-between w-full py-2 px-3 rounded md:border-0 md:p-0 md:w-auto text-white md:hover:text-blue-500 focus:text-white border-gray-700 hover:bg-gray-700 md:hover:bg-transparent"
                                                aria-current="page">
                                                <?php echo get_post_meta($orgID, 'orgname', true) ?>総選挙
                                                <i class="fa fa-angle-down" aria-hidden="true">
                                                </i>
                                            </button>
                                            <div id="dropdownNavbar<?php echo $orgID ?>"
                                                class="z-10 hidden font-normal divide-y rounded-lg shadow w-44 bg-gray-800 divide-gray-600">
                                                <ul class="py-2 text-sm text-gray-200" aria-labelledby="dropdownLargeButton">
                                                    <?php
                                                    $inner_args = array(
                                                        'post_type' => 'weighttype',
                                                        'posts_per_page' => -1, // Number of posts to retrieve
                                                        'orderby' => 'DESC',
                                                    );

                                                    $inner_query = new WP_Query($inner_args);
                                                    if ($inner_query->have_posts()) {
                                                        while ($inner_query->have_posts()) {
                                                            $inner_query->the_post();
                                                            $weightID = get_the_ID();
                                                            ?>
                                                            <li>
                                                                <a href="/fight-ranking/rank/?ranktype=<?php echo $orgID ?>&weightt=<?php echo $weightID ?>"
                                                                    class="block px-4 py-2 hover:bg-gray-600 hover:text-white">
                                                                    <?php echo get_post_meta($weightID, 'weightname', true) ?>
                                                                </a>
                                                            <?php }
                                                    } ?>
                                                    </li>
                                                </ul>
                                            </div>
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
    <main class="px-4 py-36 bg-auto bg-cover bg-center min-h-[92vh] " style="background-image: url('')">
        <div class="w-[600px] mx-auto">