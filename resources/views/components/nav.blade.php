<nav class="main-navbar">
    <div class="container">
        <ul>

            <li class="menu-item  ">
                <a href="{{ route('home') }}" class='menu-link'>
                    <span><i class="bi bi-grid-fill"></i> Dashboard</span>
                </a>
            </li>

            <li class="menu-item  has-sub">
                <a href="#" class='menu-link'>
                    <span><i class="bi bi-stack"></i> Master Data</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">

                        <ul class="submenu-group">
                            <li class="submenu-item  ">
                                <a href="component-alert.html" class='submenu-link'>Tempat Pemrosesan Akhir (TPA) </a>
                            </li>

                            <li class="submenu-item  ">
                                <a href="component-badge.html" class='submenu-link'>Tempat Pengolahan Sampah Terpadu
                                    (TPST)</a>
                            </li>

                            <li class="submenu-item  ">
                                <a href="{{ route('spaldts.index') }}" class='submenu-link'>TPS3ER</a>
                            </li>

                            <li class="submenu-item  ">
                                <a href="{{ route('spaldts.index') }}" class='submenu-link'>SPALD-T</a>
                            </li>

                            <li class="submenu-item  ">
                                <a href="{{ route('spaldts.index') }}" class='submenu-link'>SPALD-S</a>
                            </li>

                        </ul>

                    </div>
                </div>
            </li>



        </ul>
    </div>
</nav>
