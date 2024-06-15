<script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.js"></script>
<script type="text/javascript">
var statistics = <?= $statistics; ?>;
let urlCetak = "<?= base_url('admin/dashboard/cetak_laporan/'); ?>";
document.addEventListener('alpine:init', () => {
  Alpine.data('appDashboard', () => ({
      stats:{
        totalCustomers:statistics.total_customers,
        totalRevenue:statistics.total_revenue,
        totalProducts:statistics.total_products,
        totalPendingTransaction:statistics.total_pending_transaction
      },
      dataDays:  <?= $data_by_days; ?>,
      dataMonth:  <?= $data_by_month; ?>,
      dataYears:  <?= $data_by_years; ?>,
      dataTableDays(){
        return {
          items: [],
          view: 5,
          searchInput: '',
          pages: [],
          offset: 10,
          pagination: {
            total: this.dataDays.length,
            lastPage: Math.ceil(this.dataDays.length / 5),
            perPage: 5,
            currentPage: 1,
            from: 1,
            to: 1 * 5
          },
          currentPage: 1,
          sorted: {
            field: 'date',
            rule: 'desc'
          },
          initData() {
            this.items = this.dataDays.sort(this.compareOnKey('date', 'desc'))
            this.showPages()
          },
          compareOnKey(key, rule) {
            return function(a, b) { 
              if (key === 'date' || key === 'total_amount') {
                let comparison = 0
                const fieldA = a[key].toUpperCase()
                const fieldB = b[key].toUpperCase()
                if (rule === 'asc') {
                  if (fieldA > fieldB) {
                    comparison = 1;
                  } else if (fieldA < fieldB) {
                    comparison = -1;
                  }
                } else {
                  if (fieldA < fieldB) {
                    comparison = 1;
                  } else if (fieldA > fieldB) {
                    comparison = -1;
                  }
                }
                return comparison
              } else {
                if (rule === 'asc') {
                  return a.year - b.year
                } else {
                  return b.year - a.year
                }
              }
            }
          },
          checkView(index) {
            return index > this.pagination.to || index < this.pagination.from ? false : true
          },
          checkPage(item) {
            if (item <= this.currentPage + 5) {
              return true
            }
            return false
          },
          search(value) {
            if (value.length > 1) {
              const options = {
                shouldSort: true,
                keys: ['date', 'transaction_count','total_amount'],
                threshold: 0
              }                
              const fuse = new Fuse(this.dataDays, options)
              this.items = fuse.search(value).map(elem => elem.item)
            } else {
              this.items = this.dataDays
            }
            this.changePage(1)
            this.showPages()
          },
          sort(field, rule) {
            this.items = this.items.sort(this.compareOnKey(field, rule))
            this.sorted.field = field
            this.sorted.rule = rule
          },
          changePage(page) {
            if (page >= 1 && page <= this.pagination.lastPage) {
              this.currentPage = page
              const total = this.items.length
              const lastPage = Math.ceil(total / this.view) || 1
              const from = (page - 1) * this.view + 1
              let to = page * this.view
              if (page === lastPage) {
                to = total
              }
              this.pagination.total = total
              this.pagination.lastPage = lastPage
              this.pagination.perPage = this.view
              this.pagination.currentPage = page
              this.pagination.from = from
              this.pagination.to = to
              this.showPages()
            }
          },
          showPages() {
            const pages = []
            let from = this.pagination.currentPage - Math.ceil(this.offset / 2)
            if (from < 1) {
              from = 1
            }
            let to = from + this.offset - 1
            if (to > this.pagination.lastPage) {
              to = this.pagination.lastPage
            }
            while (from <= to) {
              pages.push(from)
              from++
            }
            this.pages = pages;
          },
          changeView() {
            this.changePage(1)
            this.showPages()
          },
          isEmpty() {
            return this.pagination.total ? false : true
          }
        }
      },
      dataTableMonth(){
        return {
          items: [],
          view: 5,
          searchInput: '',
          pages: [],
          offset: 10,
          pagination: {
            total: this.dataMonth.length,
            lastPage: Math.ceil(this.dataMonth.length / 5),
            perPage: 5,
            currentPage: 1,
            from: 1,
            to: 1 * 5
          },
          currentPage: 1,
          sorted: {
            field: 'month',
            rule: 'desc'
          },
          initData() {
            this.items = this.dataMonth.sort(this.compareOnKey('month', 'desc'))
            this.showPages()
          },
          compareOnKey(key, rule) {
            return function(a, b) { 
              if (key === 'year' || key === 'month' || key === 'total_amount') {
                let comparison = 0
                const fieldA = a[key].toUpperCase()
                const fieldB = b[key].toUpperCase()
                if (rule === 'asc') {
                  if (fieldA > fieldB) {
                    comparison = 1;
                  } else if (fieldA < fieldB) {
                    comparison = -1;
                  }
                } else {
                  if (fieldA < fieldB) {
                    comparison = 1;
                  } else if (fieldA > fieldB) {
                    comparison = -1;
                  }
                }
                return comparison
              } else {
                if (rule === 'asc') {
                  return a.year - b.year
                } else {
                  return b.year - a.year
                }
              }
            }
          },
          checkView(index) {
            return index > this.pagination.to || index < this.pagination.from ? false : true
          },
          checkPage(item) {
            if (item <= this.currentPage + 5) {
              return true
            }
            return false
          },
          search(value) {
            if (value.length > 1) {
              const options = {
                shouldSort: true,
                keys: ['year', 'month','transaction_count','total_amount'],
                threshold: 0
              }                
              const fuse = new Fuse(this.dataMonth, options)
              this.items = fuse.search(value).map(elem => elem.item)
            } else {
              this.items = this.dataMonth
            }
            this.changePage(1)
            this.showPages()
          },
          sort(field, rule) {
            this.items = this.items.sort(this.compareOnKey(field, rule))
            this.sorted.field = field
            this.sorted.rule = rule
          },
          changePage(page) {
            if (page >= 1 && page <= this.pagination.lastPage) {
              this.currentPage = page
              const total = this.items.length
              const lastPage = Math.ceil(total / this.view) || 1
              const from = (page - 1) * this.view + 1
              let to = page * this.view
              if (page === lastPage) {
                to = total
              }
              this.pagination.total = total
              this.pagination.lastPage = lastPage
              this.pagination.perPage = this.view
              this.pagination.currentPage = page
              this.pagination.from = from
              this.pagination.to = to
              this.showPages()
            }
          },
          showPages() {
            const pages = []
            let from = this.pagination.currentPage - Math.ceil(this.offset / 2)
            if (from < 1) {
              from = 1
            }
            let to = from + this.offset - 1
            if (to > this.pagination.lastPage) {
              to = this.pagination.lastPage
            }
            while (from <= to) {
              pages.push(from)
              from++
            }
            this.pages = pages;
          },
          changeView() {
            this.changePage(1)
            this.showPages()
          },
          isEmpty() {
            return this.pagination.total ? false : true
          }
        }
      },
      dataTableYears(){
        return {
          items: [],
          view: 5,
          searchInput: '',
          pages: [],
          offset: 10,
          pagination: {
            total: this.dataYears.length,
            lastPage: Math.ceil(this.dataYears.length / 5),
            perPage: 5,
            currentPage: 1,
            from: 1,
            to: 1 * 5
          },
          currentPage: 1,
          sorted: {
            field: 'year',
            rule: 'desc'
          },
          initData() {
            this.items = this.dataYears.sort(this.compareOnKey('year', 'desc'))
            this.showPages()
          },
          compareOnKey(key, rule) {
            return function(a, b) { 
              if (key === 'year' || key === 'total_amount') {
                let comparison = 0
                const fieldA = a[key].toUpperCase()
                const fieldB = b[key].toUpperCase()
                if (rule === 'asc') {
                  if (fieldA > fieldB) {
                    comparison = 1;
                  } else if (fieldA < fieldB) {
                    comparison = -1;
                  }
                } else {
                  if (fieldA < fieldB) {
                    comparison = 1;
                  } else if (fieldA > fieldB) {
                    comparison = -1;
                  }
                }
                return comparison
              } else {
                if (rule === 'asc') {
                  return a.year - b.year
                } else {
                  return b.year - a.year
                }
              }
            }
          },
          checkView(index) {
            return index > this.pagination.to || index < this.pagination.from ? false : true
          },
          checkPage(item) {
            if (item <= this.currentPage + 5) {
              return true
            }
            return false
          },
          search(value) {
            if (value.length > 1) {
              const options = {
                shouldSort: true,
                keys: ['year', 'transaction_count','total_amount'],
                threshold: 0
              }                
              const fuse = new Fuse(this.dataYears, options)
              this.items = fuse.search(value).map(elem => elem.item)
            } else {
              this.items = this.dataYears
            }
            this.changePage(1)
            this.showPages()
          },
          sort(field, rule) {
            this.items = this.items.sort(this.compareOnKey(field, rule))
            this.sorted.field = field
            this.sorted.rule = rule
          },
          changePage(page) {
            if (page >= 1 && page <= this.pagination.lastPage) {
              this.currentPage = page
              const total = this.items.length
              const lastPage = Math.ceil(total / this.view) || 1
              const from = (page - 1) * this.view + 1
              let to = page * this.view
              if (page === lastPage) {
                to = total
              }
              this.pagination.total = total
              this.pagination.lastPage = lastPage
              this.pagination.perPage = this.view
              this.pagination.currentPage = page
              this.pagination.from = from
              this.pagination.to = to
              this.showPages()
            }
          },
          showPages() {
            const pages = []
            let from = this.pagination.currentPage - Math.ceil(this.offset / 2)
            if (from < 1) {
              from = 1
            }
            let to = from + this.offset - 1
            if (to > this.pagination.lastPage) {
              to = this.pagination.lastPage
            }
            while (from <= to) {
              pages.push(from)
              from++
            }
            this.pages = pages;
          },
          changeView() {
            this.changePage(1)
            this.showPages()
          },
          isEmpty() {
            return this.pagination.total ? false : true
          }
        }
      }
  }));
});
</script>
<main class="h-full overflow-y-auto" x-data="appDashboard">
  <div class="container px-6 mx-auto">
    <h2
      class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
    >
      Dashboard
    </h2>
    <div
      class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-red-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-red"
    >
      <div class="flex items-center">
        <span class="flex text-2xl mr-2"><i class='bx bxs-face'></i></span>
        <span>Selamat datang!</span>
      </div>
    </div>
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
      <div
        class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
      >
        <div
          class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500"
        >
          <span class="text-2xl flex"><i class='bx bxs-user-account'></i></span>
        </div>
        <div>
          <p
            class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
          >
            Total Kustomer
          </p>
          <p
            class="text-lg font-semibold text-gray-700 dark:text-gray-200"
          x-text="stats.totalCustomers">
            
          </p>
        </div>
      </div>

      <div
        class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
      >
        <div
          class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500"
        >
          <span class="text-2xl flex"><i class='bx bx-money-withdraw'></i></span>
        </div>
        <div>
          <p
            class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
          >
            Total Pendapatan
          </p>
          <template x-if="digits_count(parseInt(stats.totalRevenue)) <= 9">
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200"
            x-text="convertToRupiah(parseInt(stats.totalRevenue))">
            </p>
          </template>
          <template x-if="digits_count(parseInt(stats.totalRevenue)) >= 10 && 
            digits_count(parseInt(stats.totalRevenue)) <= 13">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200"
            x-text="convertToRupiah(parseInt(stats.totalRevenue))">
            </p>
          </template>
          <template x-if="digits_count(parseInt(stats.totalRevenue)) > 13">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-200"
            x-text="convertToRupiah(parseInt(stats.totalRevenue))">
            </p>
          </template>         
        </div>
      </div>
      <!-- Card -->
      <div
        class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
      >
        <div
          class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500"
        >
          <span class="text-2xl flex"><i class='bx bx-laptop'></i></span>
        </div>
        <div>
          <p
            class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
          >
            Total Produk
          </p>
          <p
            class="text-lg font-semibold text-gray-700 dark:text-gray-200"
          x-text="stats.totalProducts">
          </p>
        </div>
      </div>
      <div
        class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
      >
        <div
          class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500"
        >
          <span class="text-2xl flex"><i class='bx bxs-receipt'></i></span>
        </div>
        <div>
          <p
            class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
          >
            Transaksi Pending
          </p>
          <p
            class="text-lg font-semibold text-gray-700 dark:text-gray-200"
          x-text="stats.totalPendingTransaction">
          </p>
        </div>
      </div>
    </div>

<div x-data="{ tab: 'tab1' }">
  <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Rekap Transaksi Diterima</h2>
  <ul class="flex mt-6 mb-4">
    <li class="mr-1">
      <a
         class="bg-white rounded-md py-2 px-4 font-semibold dark:bg-gray-800"  href="#" 
         :class="{ 'text-white bg-teal-500': tab == 'tab1'}"
         @click.prevent="tab = 'tab1'"
         >Per Hari</a>
    </li>
    <li class="mr-1">
      <a
         class="bg-white rounded-md py-2 px-4 font-semibold dark:bg-gray-800 "
         href="#"
         :class="{ 'text-white bg-teal-500': tab == 'tab2'}"
         @click.prevent="tab = 'tab2'"
         >Per Bulan</a
        >
    </li>
    <li class="mr-1">
      <a
         class="bg-white rounded-md py-2 px-4 font-semibold dark:bg-gray-800"
         href="#" 
         :class="{ 'text-white bg-teal-500': tab == 'tab3'}"
         @click.prevent="tab = 'tab3'"
         >Per Tahun</a
        >
    </li>
  </ul>

    <div x-show="tab == 'tab1'">
      <div class="w-full overflow-y-auto rounded-lg shadow-xs mb-6"
       x-data="dataTableDays()"
       x-init="
       initData();
       $watch('searchInput', value => {
          search(value)
        })">
      <div class="flex justify-between items-center bg-white py-4 px-2 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
      <div class="flex flex-1 justify-start">
              <div
                class="relative w-full max-w-xl focus-within:text-purple-500"
              >
                <div class="absolute inset-y-0 flex items-center pl-2">
                  <span class="text-1xl"><i class='bx bx-search-alt'></i></span>
                </div>
                <input x-model="searchInput"
                  class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input"
                  type="text"
                  placeholder="Cari @tanggal / @jumlah / @nominal transaksi"
                  aria-label="Search"
                />
              </div>
            </div>
              <select 
                class="rounded-lg block mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" 
                x-model="view" @change="changeView()">
                <option value="5">Show 5 items</option>
                <option value="10">Show 10 items</option>
                <option value="25">Show 25 items</option>
                <option value="50">Show 50 items</option>
                <option value="100">Show 100 items</option>
            </select>
      </div>

      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-wrap table-auto">
          <thead>
            <tr
              class="text-xs font-semibold border-t tracking-wide text-left text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-white"
            >
              <th class="px-4 py-3">No.</th>
              <th class="px-4 py-3">Tanggal</th>
              <th class="px-4 py-3">Jumlah Transaksi</th>
              <th class="px-4 py-3">Nominal Transaksi</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <template x-for="(item, index) in items" :key="index">
            <tr x-show="checkView(index + 1)" class="text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm" x-text="index+1"></td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.date"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.transaction_count"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="convertToRupiah(parseInt(item.total_amount))"></span>
              </td>
              
              <td class="px-4 py-3">
                  <div class="flex items-center justify-center space-x-4 text-sm">
                    <a :href="urlCetak + 'hari/' + item.date" 
                      class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                      aria-label="Print"
                    >
                      <span class="text-2xl"><i class='bx bxs-printer'></i></span>
                    </a>
                    
                  </div>
                </td>
              </tr>
              </template>
          </tbody>
        </table>
      </div>

      <div
        class="flex justify-center w-full px-4 py-4 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
      >
        <!-- Pagination -->
        <div class="flex-1"></div>
        <span class="flex items-center justify-center mt-2 sm:mt-auto sm:justify-end">
          <nav aria-label="Table navigation">
            <ul class="inline-flex items-center">
              <li>
                <button
                  class="mr-2 rounded-md focus:outline-none focus:shadow-outline-purple"
                  aria-label="Previous" @click="changePage(currentPage - 1)"
                >
                  <span class="text-2xl"><i class='bx bx-chevron-left'></i></span>
                </button>
              </li>

              <template x-for="item in pages">
                <li>
                  <button
                    class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
                    @click="changePage(item)" x-bind:class="{ 'text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600': currentPage === item }" x-text="item">
                  </button>
                </li>
              </template>

              <li>
                <button
                  class="ml-2 rounded-md focus:outline-none focus:shadow-outline-purple"
                  aria-label="Previous" @click="changePage(currentPage + 1)"
                >
                  <span class="text-2xl"><i class='bx bx-chevron-right'></i></span>
                </button>
              </li>
            </ul>
          </nav>
        </span>
        <div class="flex-1"></div>
      </div>
    </div>
    </div>

    <div x-show="tab == 'tab2'">
      <div class="w-full overflow-y-auto rounded-lg shadow-xs mb-6"
       x-data="dataTableMonth()"
       x-init="
       initData();
       $watch('searchInput', value => {
          search(value)
        })">
      <div class="flex justify-between items-center bg-white py-4 px-2 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
      <div class="flex flex-1 justify-start">
              <div
                class="relative w-full max-w-xl focus-within:text-purple-500"
              >
                <div class="absolute inset-y-0 flex items-center pl-2">
                  <span class="text-1xl"><i class='bx bx-search-alt'></i></span>
                </div>
                <input x-model="searchInput"
                  class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input"
                  type="text"
                  placeholder="Cari @tahun / @bulan / @jumlah / @nominal transaksi"
                  aria-label="Search"
                />
              </div>
            </div>
              <select 
                class="rounded-lg block mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" 
                x-model="view" @change="changeView()">
                <option value="5">Show 5 items</option>
                <option value="10">Show 10 items</option>
                <option value="25">Show 25 items</option>
                <option value="50">Show 50 items</option>
                <option value="100">Show 100 items</option>
            </select>
      </div>

      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-wrap table-auto">
          <thead>
            <tr
              class="text-xs font-semibold border-t tracking-wide text-left text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-white"
            >
              <th class="px-4 py-3">No.</th>
              <th class="px-4 py-3">Tahun</th>
              <th class="px-4 py-3">Bulan</th>
              <th class="px-4 py-3">Jumlah Transaksi</th>
              <th class="px-4 py-3">Nominal Transaksi</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <template x-for="(item, index) in items" :key="index">
            <tr x-show="checkView(index + 1)" class="text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm" x-text="index+1"></td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.year"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.month"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.transaction_count"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="convertToRupiah(parseInt(item.total_amount))"></span>
              </td>
              
              <td class="px-4 py-3">
                  <div class="flex items-center justify-center space-x-4 text-sm">
                    <a :href="urlCetak + 'bulan/' + item.month + '-' + item.year" 
                      class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                      aria-label="Edit"
                    >
                      <span class="text-2xl"><i class='bx bxs-printer'></i></span>
                    </a>
                    
                  </div>
                </td>
              </tr>
              </template>
          </tbody>
        </table>
      </div>

      <div
        class="flex justify-center w-full px-4 py-4 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
      >
        <!-- Pagination -->
        <div class="flex-1"></div>
        <span class="flex items-center justify-center mt-2 sm:mt-auto sm:justify-end">
          <nav aria-label="Table navigation">
            <ul class="inline-flex items-center">
              <li>
                <button
                  class="mr-2 rounded-md focus:outline-none focus:shadow-outline-purple"
                  aria-label="Previous" @click="changePage(currentPage - 1)"
                >
                  <span class="text-2xl"><i class='bx bx-chevron-left'></i></span>
                </button>
              </li>

              <template x-for="item in pages">
                <li>
                  <button
                    class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
                    @click="changePage(item)" x-bind:class="{ 'text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600': currentPage === item }" x-text="item">
                  </button>
                </li>
              </template>

              <li>
                <button
                  class="ml-2 rounded-md focus:outline-none focus:shadow-outline-purple"
                  aria-label="Previous" @click="changePage(currentPage + 1)"
                >
                  <span class="text-2xl"><i class='bx bx-chevron-right'></i></span>
                </button>
              </li>
            </ul>
          </nav>
        </span>
        <div class="flex-1"></div>
      </div>
    </div>
    </div>

    <div x-show="tab == 'tab3'">
       <div class="w-full overflow-y-auto rounded-lg shadow-xs mb-6"
         x-data="dataTableYears()"
         x-init="
         initData();
         $watch('searchInput', value => {
            search(value)
          })">
        <div class="flex justify-between items-center bg-white py-4 px-2 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
        <div class="flex flex-1 justify-start">
                <div
                  class="relative w-full max-w-xl focus-within:text-purple-500"
                >
                  <div class="absolute inset-y-0 flex items-center pl-2">
                    <span class="text-1xl"><i class='bx bx-search-alt'></i></span>
                  </div>
                  <input x-model="searchInput"
                    class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-600 bg-gray-100 border-0 rounded-md dark:placeholder-gray-500 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-500 focus:bg-white focus:border-purple-300 focus:outline-none focus:shadow-outline-purple form-input"
                    type="text"
                    placeholder="Cari @tahun / @jumlah / @nominal transaksi"
                    aria-label="Search"
                  />
                </div>
              </div>
                <select 
                  class="rounded-lg block mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" 
                  x-model="view" @change="changeView()">
                  <option value="5">Show 5 items</option>
                  <option value="10">Show 10 items</option>
                  <option value="25">Show 25 items</option>
                  <option value="50">Show 50 items</option>
                  <option value="100">Show 100 items</option>
              </select>
        </div>

        <div class="w-full overflow-x-auto">
          <table class="w-full whitespace-wrap table-auto">
            <thead>
              <tr
                class="text-xs font-semibold border-t tracking-wide text-left text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-white"
              >
                <th class="px-4 py-3">No.</th>
                <th class="px-4 py-3">Tahun</th>
                <th class="px-4 py-3">Jumlah Transaksi</th>
                <th class="px-4 py-3">Nominal Transaksi</th>
                <th class="px-4 py-3 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <template x-for="(item, index) in items" :key="index">
              <tr x-show="checkView(index + 1)" class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3 text-sm" x-text="index+1"></td>
                <td class="px-4 py-3 text-sm">
                  <span x-text="item.year"></span>
                </td>
                <td class="px-4 py-3 text-sm">
                  <span x-text="item.transaction_count"></span>
                </td>
                <td class="px-4 py-3 text-sm">
                  <span x-text="convertToRupiah(parseInt(item.total_amount))"></span>
                </td>
                
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center space-x-4 text-sm">
                      <a :href="urlCetak + 'tahun/' + item.year" 
                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                        aria-label="Edit"
                      >
                        <span class="text-2xl"><i class='bx bxs-printer'></i></span>
                      </a>
                    </div>
                  </td>
                </tr>
                </template>
            </tbody>
          </table>
        </div>

        <div
          class="flex justify-center w-full px-4 py-4 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
        >
          <!-- Pagination -->
          <div class="flex-1"></div>
          <span class="flex items-center justify-center mt-2 sm:mt-auto sm:justify-end">
            <nav aria-label="Table navigation">
              <ul class="inline-flex items-center">
                <li>
                  <button
                    class="mr-2 rounded-md focus:outline-none focus:shadow-outline-purple"
                    aria-label="Previous" @click="changePage(currentPage - 1)"
                  >
                    <span class="text-2xl"><i class='bx bx-chevron-left'></i></span>
                  </button>
                </li>

                <template x-for="item in pages">
                  <li>
                    <button
                      class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
                      @click="changePage(item)" x-bind:class="{ 'text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600': currentPage === item }" x-text="item">
                    </button>
                  </li>
                </template>

                <li>
                  <button
                    class="ml-2 rounded-md focus:outline-none focus:shadow-outline-purple"
                    aria-label="Previous" @click="changePage(currentPage + 1)"
                  >
                    <span class="text-2xl"><i class='bx bx-chevron-right'></i></span>
                  </button>
                </li>
              </ul>
            </nav>
          </span>
          <div class="flex-1"></div>
        </div>
      </div>
      </div>
      <!-- end tabs -->
    </div>

  </div>
</main>