# Waruga
*Source Code* Aplikasi untuk ketua RT dalam manajemen data warga dan menghitung uang kas dari iuran warga.

Silahkan ke [waruga.ibnux.org](http://waruga.ibnux.org) untuk versi yang tinggal pakai.

Aplikasi saya buat di Mac OS, dimana menggunakan *Sheel script* untuk *compile* aplikasi PHP menjadi [Phar](http://php.net/manual/en/intro.phar.php) menggunakan [empir](http://empir.sourceforge.net)

Untuk windows silahkan cari caranya :) Anda Programmer, pasti bisa cari jawabannya (^_^)v

untuk compile silahkan edit phar.sh terutama bagian `../warugar/unduh/waruga.phar` ini merupakan posisi hasil *compile* menjadi phar

lalu `chmod +x phar.sh`
selanjutnya panggil di command line  `./phar.sh`

aplikasi sederhana, dibuat tanpa keamanan memadai, semua fungsi ada di folder **include**, dimana cukup dipanggil dengan request GET `?apa=[namafile]`

menambahkan menu ada di **menu.php**

Tampilan menggunakan [Twitter Bootstrap](http://getbootstrap.com)

Saya kurang mengerti masalah lisensi perangkat lunak, maaf jika ada yang kurang biasanya ada di bagian file yg saya gunakan (css/js).

Untuk aplikasi Waruga sendiri berlisensi MIT.

====
**The MIT License**

Copyright (c) 2014 Ibnu Maksum (me@ibnux.net)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
