<div class="flex flex-col flex-1 w-full" >
      <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
        <div
          class="container flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300"
        >
          <!-- Mobile hamburger -->
          <div class="flex-1">
            <button
            class="p-1 mr-5 ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
            @click="toggleSideMenu"
            aria-label="Menu"
          >
            <span class="text-2xl"><i class='bx bx-menu'></i></span>
          </button>
          </div>
    <ul class="flex items-center space-x-6 pr-2">
      <!-- Theme toggler -->
      <li class="flex">
        <button
          class="rounded-md focus:outline-none focus:shadow-outline-purple"
          @click="toggleTheme"
          aria-label="Toggle color mode"
        >
          <template x-if="!dark">
            <span class="text-2xl"><i class='bx bxs-moon'></i></span>
          </template>
          <template x-if="dark">
            <span class="text-2xl"><i class='bx bxs-sun'></i></span>
          </template>
        </button>
      </li>
      <!-- Profile menu -->
      <li class="relative">
        <button
          class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none"
          @click="toggleProfileMenu()"
          @keydown.escape="closeProfileMenu"
          aria-label="Account"
          aria-haspopup="true"
        >
          <img
            class="object-cover w-8 h-8 rounded-full"
            src="<?= base_url('assets/img/profile.png');?>"
            alt=""
            aria-hidden="true"
          />
        </button>
          <ul 
          x-cloak
          x-show="isProfileMenuOpen"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.away="closeProfileMenu"
            @keydown.escape="closeProfileMenu"
            class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700"
            aria-label="submenu"
          >
            <li class="flex">
              <a
                class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                href="#"
              >
                <span class="text-1xl mr-3"><i class='bx bx-user'></i></span>
                <span>Profile</span>
              </a>
            </li>
            <li class="flex">
              <a
                class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                href="<?= base_url('home/logout'); ?>"
              >
                <span class="text-1xl mr-3"><i class='bx bx-log-out'></i></span>
                <span>Log out</span>
              </a>
            </li>
          </ul>
      </li>
    </ul>
  </div>
</header>

