<aside class="main-sidebar <?php if ($settings['thememode'] == 'Light') {
                                echo "sidebar-light-" . $settings['primary_color'];
                            } else {
                                echo "sidebar-dark-" . $settings['primary_color'];
                            } ?>" id="sidebar">

    <!-- Brand Logo -->
    <a href="/admin/dashboard" class="brand-link <?php echo $settings['thememode'] == 'Light' ? 'bg-white' : 'bg-dark'; ?> sb-brand-link">
        <?php if ($settings['logo'] != null) : ?>
            <img src="<?= base_url($settings['logo']) ?>"
                 alt="<?= $settings['business_name']; ?>"
                 class="brand-image sb-brand-img">
        <?php endif; ?>
        <span class="brand-text sb-brand-text"><?= $settings['business_name']; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar sb-sidebar">


        <!-- Search -->
        <div class="sidebar-search sb-search-wrap mt-1">
            <div class="form-inline">
                <div class="input-group sb-search-group" data-widget="sidebar-search">
                    <span class="sb-search-icon"><i class="fi fi-tr-magnifying-glass-eye"></i></span>
                    <input class="form-control form-control-sidebar sb-search-input"
                           id="search-menu-input"
                           type="search"
                           placeholder="Search menu…"
                           aria-label="Search">
                </div>
            </div>
        </div>

        <!-- Nav menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm sb-nav"
                data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item has-treeview mt-1">
                    <a href="/admin/dashboard" class="nav-link sb-nav-link">
                        <i class="nav-icon fi fi-tr-dashboard-monitor sb-nav-icon"></i>
                        <p class="sb-nav-text">Dashboard</p>
                    </a>
                </li>

                <?php
                $sidebarItems = get_admin_sidebar_data();
                $parents  = [];
                $children = [];

                foreach ($sidebarItems as $item) {
                    if ($item['parent_id'] == 0) {
                        $parents[] = $item;
                    } else {
                        $children[$item['parent_id']][] = $item;
                    }
                }

                function render_sidebar_item($item)
                {
                    if (can_view($item['permission_category_short_code'])) {
                        echo '<li class="nav-item">';
                        echo '<a href="' . esc($item['url']) . '" class="nav-link sb-nav-link sb-child-link">';
                        if (!empty($item['icon'])) {
                            echo '<i class="nav-icon ' . esc($item['icon']) . ' sb-nav-icon"></i>';
                        }
                        if ($item['is_it_have_badge']) {
                            echo '<p class="sb-nav-text">' . esc($item['title']) . ' <span class="right badge ' . esc($item['badge_type']) . '">' . $item['badge_function']($item['badge_function_parameter']) . '</span></p>';
                        } else {
                            echo '<p class="sb-nav-text">' . esc($item['title']) . '</p>';
                        }
                        echo '</a>';
                        echo '</li>';
                    }
                }

                foreach ($parents as $parent) {
                    if ($parent['is_it_header'] == 1) {
                        echo '<li class="nav-header sb-nav-header">' . esc($parent['title']) . '</li>';
                    } else {
                        if (can_view($parent['permission_category_short_code'])) {
                            echo '<li class="nav-item has-treeview ' . $parent['permission_category_short_code'] . '">';
                            echo '<a href="' . esc($parent['url']) . '" class="nav-link sb-nav-link">';
                            if (!empty($parent['icon'])) {
                                echo '<i class="nav-icon ' . esc($parent['icon']) . ' sb-nav-icon"></i>';
                            }
                            if (isset($children[$parent['id']])) {
                                echo '<p class="sb-nav-text">' . esc($parent['title']) . '<i class="right fi fi-tr-angle-small-right sb-caret"></i></p>';
                            } else {
                                if ($parent['is_it_have_badge'] == 1) {
                                    if ($parent['badge_function_parameter'] == 0) {
                                        echo '<p class="sb-nav-text">' . esc($parent['title']) . ' <span class="right badge ' . esc($parent['badge_type']) . '">' . esc($parent['badge_function']) . '</span></p>';
                                    }
                                } else {
                                    echo '<p class="sb-nav-text">' . esc($parent['title']) . '</p>';
                                }
                            }
                            echo '</a>';

                            if (isset($children[$parent['id']])) {
                                echo '<ul class="nav nav-treeview sb-treeview">';
                                foreach ($children[$parent['id']] as $child) {
                                    render_sidebar_item($child);
                                }
                                echo '</ul>';
                            }
                            echo '</li>';
                        }
                    }
                }
                ?>

            </ul>
        </nav>
    </div>
</aside>


