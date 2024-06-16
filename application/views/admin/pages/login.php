<body>
<script type="text/javascript">
 document.addEventListener('alpine:init', () => {
  let timer;
  let dashboard = "<?= base_url('admin/dashboard'); ?>";
  Alpine.data('appLogin', () => ({
      toastResult:{
        'status':'',
        'message':'',
        'isOpen':false
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
      doLogin() {
        return {
          formData: {},
          loading:false,
          buttonLabel: 'Log In',
          async submitData() {
            this.loading=true;
            this.buttonLabel="Loading ...";

            const newFormData = new FormData(); 
            for(const name in this.formData){
              newFormData.append(name, this.formData[name]);
            }

            const result =  await fetch('<?= base_url(); ?>home/login_aksi', {
              method: 'POST',
              body: newFormData
            }).then(function(response){
              if(response.ok) return response.json()
              return Promise.reject(response);
            }).catch((err) => {
              console.log(err);
                this.openToast('error', 'Response error');
                this.loading=false;
            });
            this.formData = {};
            this.buttonLabel='Log In';

            if(!result) return;

            if(result.code == 200){
              this.openToast(result.status, result.message);
              setTimeout(() => {
                 window.location = dashboard;
              }, 2000);
            }else{
              this.openToast(result.status, result.message);
              this.loading=false;
              this.buttonLabel='Log In';
            }
          }
        }
      }
    }));
  });
</script>
  
  <div class="flex items-center min-h-screen p-6 bg-gray-100 dark:bg-gray-900" x-data="appLogin">
    <div
      class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800"
    >
      <div class="flex flex-col overflow-y-auto md:flex-row">
        <div class="h-56 md:h-auto md:w-1/2">
          <img
            aria-hidden="true"
            class="object-cover w-full h-full"
            src="<?= base_url('assets/'); ?>img/login-office.jpeg"
            alt="Office"
          />
        </div>
        <div class="flex items-center justify-center md:w-1/2">
          <div class="w-full">
              <div class="bg-gray-350 p-6 tex-white">
                <h1 class="mb-2 text-xl font-semibold text-white dark:text-gray-200">
                Login Page
                </h1>
                <hr class="mt-2 mb-2" />
                <p class="text-xs text-white">Sodagar Komputer E-commerce</p>
              </div>
              <form class="p-6 sm:p-12" 
              x-data="doLogin()"
              @submit.prevent="submitData">
                <label class="block text-sm" >
                  <span class="text-gray-700 dark:text-gray-400">Username</span>
                  <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                    <input class="block w-full pr-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="your username" type="text" name="username" x-model="formData.username" required>
                    <div class="absolute inset-y-0 right-0 flex items-center mr-3 pointer-events-none">
                      <span class="flex text-2xl"><i class='bx bx-user-circle'></i></span>
                    </div>
                  </div>
                </label>
                
                 <label class="block mt-4 mb-4 text-sm" >
                  <span class="text-gray-700 dark:text-gray-400">Password</span>
                  <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                    <input class="block w-full pr-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="*******" type="password" name="password" x-model="formData.password" required>
                    <div class="absolute inset-y-0 right-0 flex items-center mr-3 pointer-events-none">
                      <span class="flex text-2xl"><i class='bx bx-lock-alt'></i></span>
                    </div>
                  </div>
                </label>

                <div class="flex items-center mt-2 mb-6">
                  <input id="remember-me" type="checkbox" value="" class="w-4 h-4 rounded-lg text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:bg-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray">
                  <label for="remember-me" class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-300">Remember me</label>
                </div>
                <button type="submit"
                  class="block w-full px-4 py-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                  :class="{ 'opacity-50 cursor-not-allowed': loading }"
                  x-text="buttonLabel"
                >
                  Log in
                </button>
              </form>
          </div>
        </div>
      </div>
    </div>

    <?php $this->load->view('admin/pages/toast_modal'); ?>
  </div>
</body>
</html>