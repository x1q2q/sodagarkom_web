function convertToRupiah(angka) {
  var rupiah = '';
  var angkarev = angka.toString().split('').reverse().join('');
  for (var i = 0; i < angkarev.length; i++) if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
  return 'Rp' + rupiah.split('', rupiah.length - 1).reverse().join('');
}
function sumAll(data){
  let reduce =  data.reduce((count,item)=>count+parseInt(item.total_price),0);
  return convertToRupiah(reduce);
}
function digits_count(n) {
  var count = 0;
  if (n >= 1) ++count;
  while (n / 10 >= 1) {
    n /= 10;
    ++count;
  }
  return count;
}