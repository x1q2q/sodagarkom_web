<body>
    <div
      class="flex h-screen overflow-auto bg-gray-100 dark:bg-gray-900 "
      :class="{ 'overflow-hidden': isSideMenuOpen }"
    >
      <!-- Desktop sidebar -->
      <aside
        class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0 "
      >
        <div class="py-0 text-gray-500 dark:text-gray-400">
          <div class="pl-2 w-full dark: bg-white">
            <img class="p-2 " src="<?= base_url('assets'); ?>/img/logo-sdgk.png" style="height:63px" />
          </div>
          
          <ul class="pr-2">
            <li class="relative px-6 py-3 <?php if($menu_active == 'dashboard'){ echo'bg-gray-100 dark:bg-gray-700'; } ?>">
              <?php if($menu_active == 'dashboard'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/dashboard'); ?>"
              >
                <span class="text-2xl"><i class='bx bxs-dashboard'></i></span>
                <span class="ml-4">Dashboard</span>
              </a>
            </li>
            <li class="relative px-6 py-3 <?php if($menu_active == 'customers'){ echo'bg-gray-100 dark:bg-gray-700'; } ?> ">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/customers'); ?>"
              >
              <?php if($menu_active == 'customers'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
                <span class="text-2xl"><i class='bx bxs-group' ></i></span>
                <span class="ml-4">Customers</span>
              </a>
            </li>
            <li class="relative px-6 py-3 <?php if($menu_active == 'categories'){ echo'bg-gray-100 dark:bg-gray-700'; } ?>">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/categories'); ?>"
              >
              <?php if($menu_active == 'categories'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
                <span class="text-2xl"><i class='bx bx-spreadsheet'></i></span>
                <span class="ml-4">Categories</span>
              </a>
            </li>
            <li class="relative px-6 py-3 <?php if($menu_active == 'products'){ echo'bg-gray-100 dark:bg-gray-700'; } ?> ">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/products'); ?>"
              >
              <?php if($menu_active == 'products'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
                <span class="text-2xl"><i class='bx bx-desktop' ></i></span>
                <span class="ml-4">Products</span>
              </a>
            </li>
            <li class="relative px-6 py-3 <?php if($menu_active == 'transactions'){ echo'bg-gray-100 dark:bg-gray-700'; } ?> ">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/transactions'); ?>"
              >
              <?php if($menu_active == 'transactions'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
                <span class="text-2xl"><i class='bx bxs-dollar-circle'></i></span>
                <span class="ml-4">Transactions</span>
              </a>
            </li>
            
          </ul>
        </div>
      </aside>
      <!-- Mobile sidebar -->
      <!-- Backdrop -->
      <div
        x-cloak
        x-show="isSideMenuOpen"
        x-transition:enter="transition ease-in-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
      ></div>
      <aside
        class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
        x-cloak
        x-show="isSideMenuOpen"
        x-transition:enter="transition ease-in-out duration-150"
        x-transition:enter-start="opacity-0 transform -translate-x-20"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 transform -translate-x-20"
        @click.away="closeSideMenu"
        @keydown.escape="closeSideMenu"
      >
        <div class="py-1 text-gray-500 dark:text-gray-400">
           <div class="pl-2 w-full dark: bg-white">
            <img class="p-2 " src="<?= base_url('assets'); ?>/img/logo-sdgk.png" style="height:63px" />
          </div>
          <ul class="pr-2">
            <li class="relative px-6 py-3 <?php if($menu_active == 'dashboard'){ echo'bg-gray-100 dark:bg-gray-700'; } ?>">
              <?php if($menu_active == 'dashboard'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/dashboard'); ?>"
              >
                <span class="text-2xl"><i class='bx bxs-dashboard'></i></span>
                <span class="ml-4">Dashboard</span>
              </a>
            </li>
            <li class="relative px-6 py-3 <?php if($menu_active == 'customers'){ echo'bg-gray-100 dark:bg-gray-700'; } ?>">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/customers'); ?>"
              >
              <?php if($menu_active == 'customers'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
                <span class="text-2xl"><i class='bx bxs-group' ></i></span>
                <span class="ml-4">Customers</span>
              </a>
            </li>
            <li class="relative px-6 py-3 <?php if($menu_active == 'categories'){ echo'bg-gray-100 dark:bg-gray-700'; } ?>">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/categories'); ?>"
              >
              <?php if($menu_active == 'categories'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
                <span class="text-2xl"><i class='bx bxs-spreadsheet' ></i></span>
                <span class="ml-4">Categories</span>
              </a>
            </li>
            <li class="relative px-6 py-3 <?php if($menu_active == 'products'){ echo'bg-gray-100 dark:bg-gray-700'; } ?>">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/products'); ?>"
              >
              <?php if($menu_active == 'products'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
                <span class="text-2xl"><i class='bx bx-desktop' ></i></span>
                <span class="ml-4">Products</span>
              </a>
            </li>
            <li class="relative px-6 py-3 <?php if($menu_active == 'transactions'){ echo'bg-gray-100 dark:bg-gray-700'; } ?>">
              <a
                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                href="<?= base_url('admin/transactions'); ?>"
              >
              <?php if($menu_active == 'transactions'){ ?>
              <span
                class="absolute inset-y-0 left-0 w-1 bg-red-600 rounded-tr-lg rounded-br-lg"
                aria-hidden="true"
              ></span>
              <?php } ?>
                <span class="text-2xl"><i class='bx bxs-dollar-circle'></i></span>
                <span class="ml-4">Transactions</span>
              </a>
            </li>
            
          </ul>
        </div>
      </aside>
      