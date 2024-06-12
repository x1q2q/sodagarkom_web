<script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.js"></script>
<script type="text/javascript">
 document.addEventListener('alpine:init', () => {
  let timer;
  Alpine.data('app', () => ({
      dataTB:  <?= $data; ?>,
      isModalAddOpen:false,
      isModalDeleteOpen:false,
      isModalEditOpen:false,
      toastResult:{
        'status':'',
        'message':'',
        'isOpen':false
      },
      dataEdit:{},
      closeModal(typeModal){
        this.dataTB = [];
        if(typeModal == 'add'){
          this.isModalAddOpen = false  
        }else if(typeModal == 'delete'){
          this.isModalDeleteOpen = false
        }else{
          this.isModalEditOpen=false
          this.dataEdit={}
        }
      },
      openModal(typeModal,item=null){
        if(typeModal == 'add'){
          this.isModalAddOpen = true 
        }else if(typeModal == 'delete'){
          this.isModalDeleteOpen = true
          this.dataEdit.id = item
        }else if(item != null){
          this.isModalEditOpen=true
          // use this inseatd of this.dataEdit = item, so we prevent from reactivitiy models 
          // when user editing form edit data
          this.dataEdit= {
            id: item.id,
            username: item.username,
            email: item.email,
            password: item.password,
            full_name: item.full_name,
            address: item.address,
            phone: item.phone
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
      async activateCustomer(id){
        await fetch('<?= base_url(); ?>admin/customers/activate/'+id, {
            method: 'GET'
          })
          .then(response => response.json())
          .then((result) => {
            if(result.code == 200){
               this.openToast(result.status, result.message);
               setTimeout(() => {
                   window.location.reload();
              }, 2000);
              
            }                
          });
      },
      async deleteCustomer(){
        await fetch('<?= base_url(); ?>admin/customers/delete/'+this.dataEdit.id, {
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
      customersInsert() {
        return {
          formData: {
            username: '',
            email: '',
            password: '',
            full_name: '',
            address: '',
            phone: '',
          },
          loading:false,
          buttonLabel: 'Submit',
          async submitData() {
            this.loading=true;
            this.buttonLabel="Submitting ...";

            var result =  await fetch('<?= base_url(); ?>admin/customers/insert', {
              method: 'POST',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(this.formData)
            }).then(function(response){
              if(response.ok) return response.json()
              return Promise.reject(response);
            }).catch((err) => {
                this.closeModal('add');
                this.openToast('error', 'Response error');
                this.loading=false;
                this.buttonLabel='Submit';
            });

            if(!result) return;

            if(result.code == 200){
               this.closeModal('add');
               this.openToast(result.status, result.message);
               setTimeout(() => {
                 window.location.reload();
              }, 2000);
            }else{
              this.closeModal('add');
              this.openToast(result.status, result.message);
              this.loading=false;
              this.buttonLabel='Submit';
            }
          }
        }
      },
      customersUpdate() {
        return {
          loading:false,
          buttonLabel: 'Update',
          async updateData() {
            this.loading=true;
            this.buttonLabel="Updating ...";
            var result = await fetch('<?= base_url(); ?>admin/customers/update/'+this.dataEdit.id, {
              method: 'POST',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(this.dataEdit)
            }).then(function(response){
              if(response.ok) return response.json()
              return Promise.reject(response);
            }).catch((err) => {
                this.closeModal('edit');
                this.openToast('error', 'Response error');
                this.loading=false;
                this.buttonLabel='Update';
            });


            if(!result) return;

            if(result.code == 200){
               this.closeModal('edit');
               this.openToast(result.status, result.message);
               setTimeout(() => {
                 window.location.reload();
              }, 2000);
            }else{
              this.closeModal('edit');
              this.openToast(result.status, result.message);
              this.loading=false;
              this.buttonLabel = 'Update';
            }
          }
        }
      },
      dataTable(){
        return {
          items: [],
          view: 5,
          searchInput: '',
          pages: [],
          offset: 10,
          pagination: {
            total: this.dataTB.length,
            lastPage: Math.ceil(this.dataTB.length / 5),
            perPage: 5,
            currentPage: 1,
            from: 1,
            to: 1 * 5
          },
          currentPage: 1,
          sorted: {
            field: 'created_at',
            rule: 'asc'
          },
          initData() {
            this.items = this.dataTB.sort(this.compareOnKey('created_at', 'asc'))
            this.showPages()
          },
          compareOnKey(key, rule) {
            return function(a, b) { 
              if (key === 'username' || key === 'email' || key === 'phone' || key === 'is_accepted') {
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
                keys: ['username', 'email','address','full_name','phone'],
                threshold: 0
              }                
              const fuse = new Fuse(this.dataTB, options)
              this.items = fuse.search(value).map(elem => elem.item)
            } else {
              this.items = this.dataTB
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

<main class="h-full overflow-y-auto" x-data="app">     
    <div
      x-show="isModalAddOpen"
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
    >
      <!-- Modal -->
      <form
        x-show="isModalAddOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform translate-y-1/2"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0  transform translate-y-1/2"
        @click.away="closeModal('add')"
        class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
        role="dialog"
        id="modal-add"
        x-data="customersInsert()"
        @submit.prevent="submitData" 
      >
        <div class="mt-4 mb-6">
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
            Tambah Data
          </p>
          <div class="mt-4">
            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Username</span>
              <input name="username" type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="nama pengguna"
              x-model="formData.username" required>
            </label>

            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Password</span>
              <input name="password" type="password" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="***********"
              x-model="formData.password" required>
            </label>

            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Email</span>
              <input name="email" type="email" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="email"
              x-model="formData.email" required>
            </label>

            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Nama Lengkap</span>
              <input name="full_name" type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="nama lengkap"
              x-model="formData.full_name" required>
            </label>

            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Alamat</span>
              <textarea name="address" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="alamat lengkap, kecamatan, kabupaten" x-model="formData.address" required></textarea>
            </label>
            

            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">No. Telepon</span>
              <input name="phone" type="number" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="no. telepon"
              x-model="formData.phone" required>
            </label>
          </div>
        </div>
        <footer
          class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
        >
          <button type="button" @click="closeModal('add')"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
          >
            Cancel
          </button>
          <button type="submit"

            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
            :class="{ 'opacity-50 cursor-not-allowed': loading }"
            x-text="buttonLabel" 
          >
            Submit
          </button>
        </footer>
      </form>
    </div>
    <!-- End of modal add backdrop -->

    <div
      x-show="isModalEditOpen"
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
    >
      <!-- Modal -->
      <form
        x-show="isModalEditOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform translate-y-1/2"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0  transform translate-y-1/2"
        class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
        role="dialog"
        id="modal-edit"
        x-data="customersUpdate()"
        @submit.prevent="updateData"
      >
        <div class="mt-4 mb-6">
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
            Edit Data
          </p>
            <div class="mt-4">
            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Username</span>
              <input x-ref="dataEdit.username" x-model="dataEdit.username" type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="nama pengguna" required>
            </label>

            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Email</span>
              <input x-model="dataEdit.email" type="email" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="email" required>
            </label>

            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Nama Lengkap</span>
              <input x-model="dataEdit.full_name" type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="nama lengkap" required>
            </label>

            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">Alamat</span>
              <textarea x-model="dataEdit.address" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="alamat lengkap, kecamatan, kabupaten" required></textarea>
            </label>
            

            <label class="block text-sm mt-2">
              <span class="text-gray-700 dark:text-gray-400">No. Telepon</span>
              <input x-model="dataEdit.phone" type="number" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="no. telepon" required>
            </label>
          </div>
        </div>
        <footer
          class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
        >
          <button type="button" 
            @click="closeModal('edit')"
            class="cursor-pointer w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
          >
            Cancel
          </button>
          <button type="submit" 
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-teal-500 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-teal-400 focus:outline-none focus:shadow-outline-purple"
            :class="{ 'opacity-50 cursor-not-allowed': loading }"
            x-text="buttonLabel" 
          >
            Update
          </button>
        </footer>
      </form>
    </div>
    <!-- End of modal edit backdrop -->


      <div
        x-show="isModalDeleteOpen"
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
        x-show="isModalDeleteOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 transform translate-y-1/2"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0  transform translate-y-1/2"
        @click.away="closeModal('delete')"
        class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
        role="dialog"
        id="modal-delete"
      >
        <div class="mt-4 mb-6">
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
            Delete Data
          </p>
          <div class="mt-4">
            <p class="text-sm text-gray-700 dark:text-gray-400">
              Confirm to delete this data?
            </p>
          </div>
        </div>
        <footer
          class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
        >
          <button
            @click="closeModal('delete')"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
          >
            Cancel
          </button>
          <button @click="deleteCustomer()"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-red-600  focus:outline-none focus:shadow-outline-purple"
          >
            Yes, Delete
          </button>
        </footer>
      </div>
    </div>
    <!-- End of modal delete backdrop -->

<div x-show="toastResult.isOpen" x-transition.duration.500m 
:class="toastResult.status == 'ok' ?  'bg-teal-500' : 'bg-red-600' " 
class="fixed z-30 shadow rounded-md text-white transition text-sm" role="alert"
style="right:2rem;top:5rem;width:25rem;">
    <div class="flex justify-between items-center py-2 px-4">
      <p class="font-semibold" x-text="toastResult.status"></p>
        &nbsp;•
      <p class="flex-1 font-semibold ml-2" x-text="toastResult.message">kolom input</p>
      <button type="button" @click="closeToast()" class="inline-flex flex-shrink-0 justify-center items-center rounded-lg text-teal-800 opacity-50 hover:opacity-100 focus:outline-none focus:opacity-100">
        <span class="text-2xl"><i class='bx bx-x'></i></span>
      </button>
    </div>
  </div>
  <!-- end of toast -->

  <div class="container px-6 mx-auto">
    <div class="flex justify-between items-center">
      <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Customers
      </h2>

      <button @click="openModal('add')"
      class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <span>Tambah Customer</span>
          <svg class="w-5 h-5 ml-2" data-slot="icon" fill="none" stroke-width="2.5" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
          </svg>
        </button>
    </div>

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
                  placeholder="Cari @pengguna / @email / @alamat"
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
              class="text-xs font-semibold border-t tracking-wide text-left text-white uppercase border-b dark:border-gray-700 bg-purple-600"
            >
              <th class="px-4 py-3">ID</th>
              <th class="px-4 py-3">Pengguna</th>
              <th class="px-4 py-3">Email</th>
              <th class="px-4 py-3">Alamat</th>
              <th class="px-4 py-3">No. Telepon</th>
              <th class="px-4 py-3 text-center">Status</th>
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
                <span x-text="item.username"></span><br>
                <span class="text-xs">(<teks x-text="item.full_name"></teks>)</span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.email"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.address"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.phone"></span>
              </td>
              <td class="px-4 py-3 text-xs text-center whitespace-no-wrap">
                <span
                  class="px-2 py-1 font-semibold leading-tight rounded-full" x-text="item.is_accepted == 1 ? 'Aktif':'Belum diaktifkan' " :class="item.is_accepted == 1 ? 'text-green-700 bg-green-100 dark:text-green-100 dark:bg-green-700' : 'text-red-700 bg-red-100 dark:bg-red-600 dark:text-white'" >
                </span>
                <template x-if="item.is_accepted == 0">
                  <div class="flex">
                    <button @click="activateCustomer(item.id)" class="flex-1 flex items-center justify-center mt-2 px-2 py-1 text-sm font-small leading-5 text-white transition-colors duration-150 bg-teal-500 border border-transparent rounded-md active:bg-green-700 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                  <span class="text-1xl"><i class='bx bxs-check-circle'></i></span>
                    <span>Aktifkan</span>
                  </button>
                  </div>
                </template>
              </td>
              
              <td class="px-4 py-3">
                  <div class="flex items-center space-x-4 text-sm">
                    <button @click="openModal('edit', item)"
                      class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                      aria-label="Edit"
                    >
                      <span class="text-2xl"><i class='bx bxs-edit-alt'></i></span>
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
                  class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple"
                  aria-label="Previous" @click="changePage(currentPage - 1)"
                >
                  <svg
                    aria-hidden="true"
                    class="w-4 h-4 fill-current"
                    viewBox="0 0 20 20"
                  >
                    <path
                      d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                      fill-rule="evenodd"
                    ></path>
                  </svg>
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
                  class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple"
                  aria-label="Next" @click="changePage(currentPage + 1)"
                >
                  <svg
                    class="w-4 h-4 fill-current"
                    aria-hidden="true"
                    viewBox="0 0 20 20"
                  >
                    <path
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd"
                      fill-rule="evenodd"
                    ></path>
                  </svg>
                </button>
              </li>
            </ul>
          </nav>
        </span>
        <div class="flex-1"></div>
      </div>
  </div>
</div>
</main>
