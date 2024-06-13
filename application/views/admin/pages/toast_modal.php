<div
    x-cloak
    x-show="toastResult.isOpen"
    class="fixed z-30 flex items-center bg-black sm:items-center sm:justify-center"
    style="left:0;right:0;bottom:0;height:80px;opacity: 0.75;"
  >
<div x-cloak x-show="toastResult.isOpen" 
    x-transition.duration.500
    :class="toastResult.status == 'ok' ?  'bg-teal-500' : 'bg-red-600' " 
    class="fixed shadow-xl ml-4 rounded-md text-white transition text-sm" role="alert"
    style="bottom:1rem;z-index: 33;">
    <div class="flex justify-between items-center py-2 px-4">
      <p class="font-semibold" x-text="toastResult.status"></p>
        &nbsp;•
      <p class="flex-1 font-semibold ml-2" x-text="toastResult.message"></p>
      <button type="button" @click="closeToast()" class="inline-flex flex-shrink-0 justify-center items-center rounded-lg text-teal-800 opacity-50 hover:opacity-100 focus:outline-none focus:opacity-100">
        <span class="text-2xl"><i class='bx bx-x'></i></span>
      </button>
    </div>
  </div>
</div>
<!-- end of toast -->