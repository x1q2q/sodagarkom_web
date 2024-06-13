<script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.js"></script>
<script type="text/javascript">
 var productAssets = "<?= $product_assets; ?>";
 document.addEventListener('alpine:init', () => {
  let timer;
  Alpine.data('appProduct', () => ({
      dataSources:  <?= $data; ?>,
      isModalOpen:{
        'modalAdd':false,
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
        if(typeModal == 'add'){
          this.isModalOpen.modalAdd = false  
        }else if(typeModal == 'delete'){
          this.isModalOpen.modalDelete = false
        }else{
          this.isModalOpen.modalEdit=false
          this.dataEdit={}
        }
      },
      openModal(typeModal,item=null){
        if(typeModal == 'add'){
          this.isModalOpen.modalAdd = true 
        }else if(typeModal == 'delete'){
          this.isModalOpen.modalDelete = true
          this.dataEdit.id = item
        }else if(typeModal == 'edit' && item != null){
          this.isModalOpen.modalEdit=true
          // use this inseatd of this.dataEdit = item, so we prevent from reactivitiy models 
          // when user editing form edit data
          this.dataEdit= {
            id: item.id,
            category_id: item.category_id,
            name: item.name,
            description: item.description,
            price: item.price,
            stock: item.stock
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
        await fetch('<?= base_url(); ?>admin/products/delete/'+this.dataEdit.id, {
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
      productsInsert() {
        return {
          formData: {
            name: '',
            description: '',
          },
          loading:false,
          buttonLabel: 'Submit',
          async submitData() {
            this.loading=true;
            this.buttonLabel="Submitting ...";

            var result =  await fetch('<?= base_url(); ?>admin/products/insert', {
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
      productsUpdate() {
        return {
          loading:false,
          buttonLabel: 'Update',
          async updateData() {
            this.loading=true;
            this.buttonLabel="Updating ...";
            var result = await fetch('<?= base_url(); ?>admin/products/update/'+this.dataEdit.id, {
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
              if (key === 'name' || key === 'description' || key === 'price' || key === 'stock' ) {
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
                keys: ['name', 'description','price','stock'],
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

<main class="h-full overflow-y-auto" x-data="appProduct">
  <div class="container px-6 mx-auto">
    <div class="flex justify-between items-center">
      <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Products
      </h2>

      <button @click="openModal('add')"
      class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <span>Tambah Product</span>
          <span class="text-2xl ml-2"><i class='bx bxs-plus-circle'></i></span>
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
                  placeholder="Cari @nama / @deskripsi produk / @harga / @stok"
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
              <th class="px-4 py-3">Kategori</th>
              <th class="px-4 py-3">Nama Produk</th>
              <th class="px-4 py-3">Harga</th>
              <th class="px-4 py-3">Stok</th>
              <th class="px-4 py-3 text-center">Thumb. Gambar</th>
              <th class="px-4 py-3">Deskripsi Produk</th>
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
                <span x-text="item.name_category"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.name"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.price"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.stock"></span>
              </td>
              <td class="px-4 py-3 text-sm">
                <template x-if="item.image_thumb !== '' ">
                  <img x-bind:src="productAssets + item.image_thumb" style="width:8rem">
                </template>
                <template x-if="item.image_thumb == '' ">
                  <span>-</span>
                </template>
              </td>
              <td class="px-4 py-3 text-sm">
                <span x-text="item.description"></span>
              </td>            
              
              <td class="px-4 py-3">
                  <div class="flex items-center justify-center space-x-4 text-sm">
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

<?php $this->load->view('admin/pages/toast_modal'); ?>
<?php $this->load->view('admin/pages/modal_delete'); ?>

<div
  x-cloak
  x-show="isModalOpen.modalEdit"
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
    x-show="isModalOpen.modalEdit"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 transform translate-y-1/2"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0  transform translate-y-1/2"
    class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
    role="dialog"
    id="modal-edit-product"
    x-data="productsUpdate()"
    @submit.prevent="updateData"
  >
    <div class="mt-4 mb-6">
      <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
        Edit Data
      </p>
        <div class="mt-4">
        <label class="block text-sm mt-2">
          <span class="text-gray-700 dark:text-gray-400">Nama</span>
          <input x-model="dataEdit.name" type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="nama produk" required>
        </label>

        <label class="block text-sm mt-2">
          <span class="text-gray-700 dark:text-gray-400">Kategori</span>
          <select 
              class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" 
              x-model="dataEdit.category_id" required>
              <option value="" hidden>pilih kategori</option>
              <?php foreach($categories as $result): ?>
              <option value="<?= $result->id; ?>" 
                :selected="formData.category_id == <?= $result->id; ?>"><?= $result->name; ?></option>
              <?php endforeach; ?>
          </select>
        </label>

        <div class="flex mt-2 justify-between items-center">
          <label class="flex-1 text-sm mr-1">
            <span class="text-gray-700 dark:text-gray-400">Harga</span>
            <input x-model="dataEdit.price" type="number" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="jumlah harga" required>
          </label>

          <label class="flex-1 text-sm ml-1">
            <span class="text-gray-700 dark:text-gray-400">Stok</span>
            <input x-model="dataEdit.stock" type="number" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="jumlah stok" required>
          </label>
        </div>

        <label class="block text-sm mt-2">
          <span class="text-gray-700 dark:text-gray-400">Upload File</span>
          <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" aria-describedby="file_input_category" id="file_input" type="file">
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_category">PNG, JPG or JPEG (MAX. 5mb).</p>
        </label>

        <label class="block text-sm mt-2">
          <span class="text-gray-700 dark:text-gray-400">Deskripsi</span>
          <textarea x-model="dataEdit.description" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="deskripsi kategori" required></textarea>
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
    x-cloak
    x-show="isModalOpen.modalAdd"
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
      x-show="isModalOpen.modalAdd"
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0 transform translate-y-1/2"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0  transform translate-y-1/2"
      @click.away="closeModal('add')"
      class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
      role="dialog"
      id="modal-add-product"
      x-data="productsInsert()"
      @submit.prevent="submitData" 
    >
      <div class="mt-4 mb-6">
        <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
          Tambah Data
        </p>
        <div class="mt-4">
          <label class="block text-sm mt-2">
            <span class="text-gray-700 dark:text-gray-400">Nama</span>
            <input name="name" type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="nama produk"
            x-model="formData.name" required>
          </label>

          <label class="block text-sm mt-2">
            <span class="text-gray-700 dark:text-gray-400">Kategori</span>
            <select 
                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" 
                x-model="formData.category_id" required>
                <option value="" hidden>pilih kategori</option>
                <?php foreach($categories as $result): ?>
                <option value="<?= $result->id; ?>"><?= $result->name; ?></option>
                <?php endforeach; ?>
            </select>
          </label>

          <div class="flex mt-2 justify-between items-center">
            <label class="flex-1 text-sm mr-1">
              <span class="text-gray-700 dark:text-gray-400">Harga</span>
              <input name="price" type="number" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="harga produk @rupiah"
              x-model="formData.price" required>
            </label>

            <label class="flex-1 text-sm ml-1">
              <span class="text-gray-700 dark:text-gray-400">Stok</span>
              <input name="stock" type="number" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="stok produk @pcs"
              x-model="formData.stock" required>
            </label>
          </div>

          <label class="block text-sm mt-2">
            <span class="text-gray-700 dark:text-gray-400">Upload File</span>
            <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" aria-describedby="file_input_category" id="file_input" type="file">
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_category">PNG, JPG or JPEG (MAX. 5mb).</p>
          </label>

         <label class="block text-sm mt-2">
            <span class="text-gray-700 dark:text-gray-400">Deskripsi</span>
            <textarea name="description" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="deskripsi produk" x-model="formData.description" required></textarea>
          </label>
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
</main>
