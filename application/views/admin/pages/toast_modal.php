<div
    x-cloak
    x-show="toastResult.isOpen"
    class="fixed flex items-center sm:items-center sm:justify-center"
    style="left:0;right:0;bottom:0;background:rgba(0,0,0,0.70);height:80px;z-index:35"
  >
<div x-cloak x-show="toastResult.isOpen" 
    x-transition.duration.500
    :class="toastResult.status == 'ok' ?  'bg-green-600' : 'bg-red-600' " 
    class="flex text-center shadow-xl rounded-md text-white transition text-sm" role="alert"
    style="bottom:1rem;z-index: 40!important;margin: auto!important;">
    <div class="flex justify-between items-center py-2 px-4">
      <p class="font-semibold" x-text="toastResult.status"></p>
        &nbsp;•
      <p class="flex-1 font-semibold ml-2" x-text="toastResult.message"></p>
      <button type="button" @click="closeToast()" class="flex items-center text-teal-800 opacity-50 hover:opacity-100 focus:outline-none">
        <span class="text-2xl"><i class='bx bx-x'></i></span>
      </button>
    </div>
  </div>
</div>
<!-- end of toast -->