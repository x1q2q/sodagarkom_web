<script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.js"></script>
<script type="text/javascript">
 var paymentAsset = "<?= $payment_assets; ?>";
 var assetsImg = "<?= base_url('assets/img/'); ?>";
 document.addEventListener('alpine:init', () => {
  let timer;
  Alpine.data('appTransaction', () => ({
      dataSources:  <?= $data; ?>,
      isModalOpen:{
        'modalDelete':false,
        'modalPayment':false,
        'modalDetail':false
      },
      toastResult:{
        'status':'',
        'message':'',
        'isOpen':false
      },
      dataDetail:{},
      dataDetailTransactions:[],
      closeModal(typeModal){
        if(typeModal == 'delete'){
          this.isModalOpen.modalDelete = false
        }else if(typeModal == 'payment'){
          this.isModalOpen.modalPayment=false
        }else if(typeModal == 'detail'){
          this.isModalOpen.modalDetail=false
        }
      },
      openModal(typeModal, item){
        if(typeModal == 'delete'){
          this.isModalOpen.modalDelete = true
        }else if(typeModal == 'payment' && item != null){
          this.isModalOpen.modalPayment=true
        }else if(typeModal == 'detail' && item != null){
          this.isModalOpen.modalDetail = true;
          this.getDetailTransactions(item.id);
        }
        this.dataDetail= {
            'id': item.id,
            'payment_proof': item.payment_proof,
            'customer_name': item.customer_name,
            'total_amount': item.total_amount,
            'total_shipping':item.total_shipping,
            'created_at':item.created_at
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
        await fetch('<?= base_url(); ?>admin/transactions/delete/'+this.dataDetail.id, {
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
      async getDetailTransactions(id){
        await fetch('<?= base_url(); ?>admin/transactions/transaction_details/'+id, {
              method: 'GET',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
            })
          .then(response => response.json())
          .then((result) => {
            if(result.code == 200){
              this.dataDetailTransactions = result.data;          
            }else{
              this.dataDetailTransactions = [];
            }
          });
      },
      async handlePaymentProof(handleTo){
        let handle = handleTo+'/';
        await fetch('<?= base_url(); ?>admin/transactions/handle_payment_proof/'+handle+this.dataDetail.id, {
              method: 'GET',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
            })
          .then(response => response.json())
          .then((result) => {
            if(result.code == 200){
               this.closeModal('payment');
               this.openToast(result.status, result.message);
               setTimeout(() => {
                   window.location.reload();
              }, 2000);                  
            }
             this.closeModal('payment');       
          });
      },
      dataTable(){
        return {
          items: [],
          view: 5,
          searchInput: '',
          pages: [],
          offset: 15,
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
                <span x-text="convertToRupiah(parseInt(item.total_amount))"></span>
              </td>
              <td class="px-4 py-3 text-sm text-center">
                <template x-if="item.status == 'rejected'">
                  <span class="text-xs">transaksi dibatalkan</span>
                </template>
                <template x-cloak x-if="item.status == 'accepted'">
                  <div class="flex justify-center bg-gray-50 p-2">                    
                    <template x-if="item.payment_proof == '' || item.payment_proof == null">
                      <img x-bind:src="assetsImg + 'no-image.png' " style="max-height:100px">
                    </template>
                    <template x-if="item.payment_proof != '' && item.payment_proof != null">
                      <img :src="paymentAsset + item.payment_proof" style="max-height:100px" onerror="this.src='<?= base_url('assets/img/'); ?>no-image.png'">
                    </template>
                  </div>
                </template>
                <template x-if="item.status == 'reserved'">
                  <span class="text-xs">belum ada pembayaran</span>
                </template>
                <template x-if="item.status == 'pending'">
                  <div>
                    <span class="text-xs">sudah ada pembayaran</span>
                    <button @click="openModal('payment', item)" class="w-full flex items-center justify-center mt-2 px-2 py-1 text-sm font-small leading-5 text-white transition-colors duration-150 bg-teal-500 border border-transparent rounded-md active:bg-green-700 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                    <span class="text-2xl"><i class='bx bx-list-check'></i></span>
                      <span>Lihat Bukti</span>
                    </button>
                  </div>
                </template>
              </td>
              <td class="px-4 py-3 text-sm">
                <template x-if="item.status == 'reserved' ">
                  <span
                    class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700" x-text="item.status">
                  </span>
                </template>
                <template x-if="item.status == 'accepted' ">
                  <span
                    class="px-2 py-1 font-semibold leading-tight rounded-full text-green-700 bg-green-100 dark:text-green-100 dark:bg-green-700" x-text="item.status">
                  </span>
                </template>
                <template x-if="item.status == 'pending' ">
                  <span
                    class="px-2 py-1 font-semibold leading-tight rounded-full text-yellow-600 bg-orange-100 dark:text-white dark:bg-yellow-400" x-text="item.status">
                  </span>
                </template>
                <template x-if="item.status == 'rejected' ">
                  <span
                    class="px-2 py-1 font-semibold leading-tight rounded-full text-red-700 bg-red-100 dark:bg-red-600 dark:text-white" x-text="item.status">
                  </span>
                </template>
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
                    <button @click="openModal('delete',item)"
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

<div
  x-cloak
  x-show="isModalOpen.modalDetail"
  x-transition:enter="transition ease-out duration-150"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in duration-150"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
>
  <!-- Modal -->
  <div
    x-show="isModalOpen.modalDetail"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 transform translate-y-1/2"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0  transform translate-y-1/2"
    @click.away="closeModal('detail')"
    class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
    role="dialog"
    id="modal-detail"
  >
    <div class="mt-4 mb-6">
      <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
        Detail Transaksi
      </p>
      <div class="mt-4 mb-4">
        <p class="text-sm text-gray-700 dark:text-gray-400">
          Berikut detail transaksi dari <b><span x-text="dataDetail.customer_name"></span></b> pada tanggal <span x-text="dataDetail.created_at"></span>
        </p>
      </div>
      <div  style="max-height: 280px!important;overflow-y: auto!important;">
        <table class="w-full whitespace-wrap border dark:border-gray-700">
          <thead>
              <tr class="rounded-md text-xs font-semibold border-t tracking-wide text-left text-white uppercase border-b dark:border-gray-700 bg-purple-600">
                <th class="p-2">Produk</th>
                <th class="p-2">Kuantitas</th>
                <th class="p-2">Harga Total</th>
              </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" >
            <template x-for="details in dataDetailTransactions">
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3 text-sm">
                  <span x-text="details.product_name"></span>
                </td>
                <td class="px-4 py-3 text-sm">
                  <span x-text="details.quantity"></span>
                </td>
                <td class="px-4 py-3 text-sm">
                  <span x-text="convertToRupiah(details.total_price)"></span>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
      <table class="w-full mt-2 whitespace-wrap table-auto border dark:border-gray-700">
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-xs font-semibold border-t tracking-wide text-left tuppercase border-b dark:border-gray-700">
            <td class="px-4 py-3 text-sm bg-gray-100">Total Harga (<span x-text="dataDetailTransactions.length"></span> item)</td>
            <td class="px-4 py-3 text-sm dark:text-white" style="text-align:right;"> <span x-text="sumAll(dataDetailTransactions)"></span></td>
          </tr>
          <tr class="text-xs font-semibold border-t tracking-wide text-left tuppercase border-b dark:border-gray-700">
            <td class="px-4 py-3 text-sm bg-gray-100">Biaya Ongkos Kirim</td>
            <td class="px-4 py-3 text-sm dark:text-white" style="text-align:right;" x-text="convertToRupiah(parseInt(dataDetail.total_shipping))"></td>
          </tr>
         <tr class="text-xs font-semibold border-t tracking-wide text-left tuppercase border-b dark:border-gray-700">
            <td class="px-4 py-3 text-sm bg-gray-100">Total Pembayaran Transaksi</td>
            <td class="px-4 py-3 text-sm dark:text-white" style="text-align:right;" x-text="convertToRupiah(parseInt(dataDetail.total_amount))"></td>
          </tr>
        </tbody>
      </table>
           
    </div>
    <footer
      class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
    >
      <button @click="closeModal('detail')"
        class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 focus:outline-none focus:shadow-outline-purple"
      >
        Close
      </button>
    </footer>
  </div>
</div>
<!-- End of modal detail backdrop -->


<div
    x-cloak
    x-show="isModalOpen.modalPayment"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
  >
    <!-- Modal -->
    <div
      x-cloak
      x-show="isModalOpen.modalPayment"
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0 transform translate-y-1/2"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0  transform translate-y-1/2"
      @click.away="closeModal('payment')"
      class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
      role="dialog"
      id="modal-payment"
    >
      <div class="mt-4 mb-6">
        <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
          Bukti Pembayaran
        </p>
        <div class="mt-4">
          <p class="text-sm text-gray-700 dark:text-gray-400">
            Konfirmasi bukti pembayaran, atau batalkan transaksi ini?
          </p>
            <div class="flex p-2 rounded-lg justify-center bg-gray-50">
              <template x-if="dataDetail.payment_proof == '' || dataDetail.payment_proof == null || dataDetail.payment_proof == undefined">
                <i class="text-red-700">*Tidak ada gambar, transaksi tidak bisa dikonfirmasi!</i>
              </template>
              <template x-if="dataDetail.payment_proof != '' && dataDetail.payment_proof != null && dataDetail.payment_proof != undefined">
                <img class="rounded-lg" :src="paymentAsset + dataDetail.payment_proof" style="max-height: 350px;" onerror="this.src='<?= base_url('assets/img/'); ?>no-image.png'" >
              </template>
            </div>  
        </div>
      </div>
      <footer
        class="flex flex-col items-center justify-center px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
      >
        <button @click="handlePaymentProof('rejected')"
          class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-red-600  focus:outline-none focus:shadow-outline-purple"
        >
          Batalkan Transaksi
        </button>
        <template x-if="dataDetail.payment_proof == '' || dataDetail.payment_proof == null || dataDetail.payment_proof == undefined">
          <button disabled 
          class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-teal-500 opacity-50 cursor-not-allowed border border-transparent disabled rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-teal-400 focus:outline-none focus:shadow-outline-purple"
        >
          Ya, Konfirmasi
        </button>
          </template>
          <template x-if="dataDetail.payment_proof != '' && dataDetail.payment_proof != null && dataDetail.payment_proof != undefined">
            <button @click="handlePaymentProof('accepted')"
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-teal-500 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-teal-400 focus:outline-none focus:shadow-outline-purple"
            >
              Ya, Konfirmasi
            </button>
          </template>
        
      </footer>
    </div>
  </div>
  <!-- End of modal payment backdrop -->

<?php $this->load->view('admin/pages/modal_delete'); ?>
<?php $this->load->view('admin/pages/toast_modal'); ?>
</main>
