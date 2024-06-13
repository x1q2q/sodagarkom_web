<script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.js"></script>
<script type="text/javascript">
 var paymentAsset = "<?= $payment_assets; ?>";
 document.addEventListener('alpine:init', () => {
  let timer;
  Alpine.data('appTransaction', () => ({
      dataSources:  <?= $data; ?>,
      isModalOpen:{
        'modalDelete':false,
        'modalEdit':false
      },
      toastResult:{
        'status':'',
        'message':'',
        'isOpen':false
      },
      dataEdit:{},
      closeModal(typeModal){
        if(typeModal == 'delete'){
          this.isModalOpen.modalDelete = false
        }else{
          this.isModalOpen.modalEdit=false
          this.dataEdit={}
        }
      },
      openModal(typeModal,item=null){
        if(typeModal == 'delete'){
          this.isModalOpen.modalDelete = true
          this.dataEdit.id = item
        }else if(typeModal == 'edit' && item != null){
          this.isModalOpen.modalEdit=true
          // use this inseatd of this.dataEdit = item, so we prevent from reactivitiy models 
          // when user editing form edit data
          this.dataEdit= {
            id: item.id,
            name: item.name,
            description: item.description
          }
        }       
      },
      openToast(status, message) {
          if (this.toastResult.isOpen) return;
          this.toastResult.message = message;
          this.toastResult.status = status;
          this.toastResult.isOpen = true;
          clearTimeout(timer);
          timer = setTimeout(() => {
              this.toastResult.isOpen = false;
          }, 5000);
      },
      closeToast() {
        this.toastResult.isOpen = false;
      },
      async deleteData(){
        await fetch('<?= base_url(); ?>admin/transaction/delete/'+this.dataEdit.id, {
              method: 'GET',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
            })
          .then(response => response.json())
          .then((result) => {
            if(result.code == 200){
               this.closeModal('delete');
                 
                 this.openToast(result.status, result.message);
                 setTimeout(() => {
                     window.location.reload();
                }, 2000);                  
            }
             this.closeModal('delete');       
          });
      },
      dataTable(){
        return {
          items: [],
          view: 5,
          searchInput: '',
          pages: [],
          offset: 10,
          pagination: {
            total: this.dataSources.length,
            lastPage: Math.ceil(this.dataSources.length / 5),
            perPage: 5,
            currentPage: 1,
            from: 1,
            to: 1 * 5
          },
          currentPage: 1,
          sorted: {
            field: 'id',
            rule: 'desc'
          },
          initData() {
            this.items = this.dataSources.sort(this.compareOnKey('id', 'desc'))
            this.showPages()
          },
          compareOnKey(key, rule) {
            return function(a, b) { 
              if (key === 'customer_name' || key === 'status' || key === 'created_at') {
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
                keys: ['customer_name', 'status','created_at'],
                threshold: 0
              }                
              const fuse = new Fuse(this.dataSources, options)
              this.items = fuse.search(value).map(elem => elem.item)
            } else {
              this.items = this.dataSources
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

<main class="h-full overflow-y-auto" x-data="appTransaction">
    

  <div class="container px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Transactions
    </h2>

    <div class="w-full overflow-y-auto rounded-lg shadow-xs mb-6"
       x-data="dataTable()"
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
                  placeholder="Cari @nama kustomer / @status / @dibuat"
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
              class="text-xs font-semibold border-t tracking-wide text-left text-white uppercase border-b dark:border-gray-700 bg-red-600"
            >
              <th class="px-4 py-3">No.</th>
              <th class="px-4 py-3">Nama Kustomer</th>
              <th class="px-4 py-3">Total Pembayaran</th>
              <th class="px-4 py-3 text-center">Bukti Pembayaran</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3">Dibuat</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody
            class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
          >
          <template x-for="(item, index) in items" :key="index">
            <tr x-show="checkView(index + 1)" class="text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm" x-text="index+1"></td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.customer_name"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="convertToRupiah(item.total_amount)"></span>
              </td>
              <td class="px-4 py-3 text-sm text-center">
                <template x-if="item.status == 'accepted'">
                  <img x-bind:src="paymentAsset + item.payment_proof" class="w-56">
                </template>
                <template x-if="item.status == 'pending' && item.payment_proof === ''">
                  <span class="text-xs">belum ada pembayaran</span>
                </template>
                <template x-if="item.status == 'pending' && item.payment_proof !== '' ">
                  <div>
                    <span class="text-xs">sudah ada pembayaran</span>
                    <button class="w-full flex items-center justify-center mt-2 px-2 py-1 text-sm font-small leading-5 text-white transition-colors duration-150 bg-teal-500 border border-transparent rounded-md active:bg-green-700 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                    <span class="text-2xl"><i class='bx bx-list-check'></i></span>
                      <span>Lihat Bukti</span>
                    </button>
                  </div>
                </template>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.status"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.created_at"></span>
              </td>
              
              <td class="px-4 py-3">
                  <div class="flex items-center justify-center space-x-4 text-sm">
                    <button @click="openModal('detail', item)"
                      class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                      aria-label="Detail"
                    >
                    <span class="text-2xl"><i class='bx bxs-show'></i></span>
                    </button>
                    <button @click="openModal('delete',item.id)"
                      class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                      aria-label="Delete"
                    >
                      <span class="text-2xl"><i class='bx bxs-trash'></i></span>
                    </button>
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

<?php $this->load->view('admin/pages/modal_delete'); ?>
<?php $this->load->view('admin/pages/toast_modal'); ?>
</main>
