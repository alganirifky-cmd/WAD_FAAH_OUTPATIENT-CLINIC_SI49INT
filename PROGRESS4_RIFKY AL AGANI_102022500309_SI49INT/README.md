# Laravel Outpatient Clinic + Authorization sederhana

## Aturan akses
| Aksi | Guest | Staff | Admin |
|---|---|---|---|
| Lihat daftar obat (`/drugs`) | redirect ke login | boleh | boleh |
| Tambah / ubah / hapus obat | redirect ke login | **403** | boleh |

## Cara pasang (di atas project clinic yang sudah jalan)
1. Salin isi folder ini ke root project (timpa `routes/web.php`, `bootstrap/app.php`,
   `layouts/app.blade.php`, `drugs/index.blade.php`, `DatabaseSeeder.php`).
2. `php artisan migrate --seed`
3. `php artisan serve` lalu login:
   - admin@clinic.test / password  (admin)
   - staff@clinic.test / password  (staff)
4. `php artisan test` (jika `tests/Feature/ExampleTest.php` masih bawaan/ubahan lama, ubah
   agar mengharapkan `assertRedirect('/drugs')`).

## Bagian yang penting
- `app/Http/Middleware/EnsureUserHasRole.php` — middleware `role:admin`
- `bootstrap/app.php` — alias `role`
- `routes/web.php` — semua aturan akses ada di satu file
- Tombol Add/Edit/Delete disembunyikan untuk staff **dan** route-nya tetap dijaga di server.
