<?php

namespace App\Controllers;

use App\Models\ModelUsers;
use App\Models\ModelBarang;
use App\Models\ModelTransaksi;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Password;

class Latihan extends BaseController
{
    protected $modelUsers, $session, $config, $auth, $userModel, $db, $modelBarang, $modelTransaksi;

    public function __construct()
    {
        $this->db           = \Config\Database::connect();
        $this->session      = service('session');

        $this->config       = config('Auth');
        $this->auth         = service('authentication');
        $this->modelUsers   = new ModelUsers();
        $this->modelBarang   = new ModelBarang();
        $this->modelTransaksi   = new ModelTransaksi();
    }

    public function index(): string
    {
        $role = $this->modelUsers->select('name as role')
            ->join('auth_groups_users', 'auth_groups_users.user_id =  users.id')
            ->join('auth_groups', 'auth_groups.id =  auth_groups_users.group_id')
            ->find(user()->id);

        $data = [
            'title' => 'Latihan',
            'role'  => $role
        ];
        return view('latihan/index', $data);
    }

    public function transaksi()
    {
        $data['title'] = 'Daftar Transaksi';
        return view('latihan/transaksi', $data);
    }

    public function dataUsers()
    {
        $dataUsers = $this->modelUsers->select('email, username, users.id, auth_groups.name as role')
            ->join('auth_groups_users', 'auth_groups_users.user_id =  users.id')
            ->join('auth_groups', 'auth_groups.id =  auth_groups_users.group_id')
            ->findAll();
        $data = [
            'title' => 'Daftar Users',
            'users' => $dataUsers,
        ];
        return view('latihan/dataUsers', $data);
    }

    public function hapusUsers($id)
    {
        $this->modelUsers->where('id', $id)->delete();
        return redirect()->back();
    }

    public function tambahUsers()
    {
        $data = [
            'title' => 'Tambah Users'
        ];
        return view('latihan/tambahUsers', $data);
    }

    public function simpanUsers()
    {
        if (!$this->config->allowRegistration) {
            return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
        }

        $users = model(UserModel::class);

        $rules = config('Validation')->registrationRules ?? [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $rules = [
            'password'     => 'required|strong_password',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
        $user              = new User($this->request->getPost($allowedPostFields));

        $this->config->requireActivation === null ? $user->activate() : $user->generateActivateHash();

        $userGroup = $this->request->getVar('role');

        if (!empty($userGroup)) {
            $users = $users->withGroup($userGroup);
        }

        if (!$users->save($user)) {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        if ($this->config->requireActivation !== null) {
            $activator = service('activator');
            $sent      = $activator->send($user);

            if (!$sent) {
                return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.unknownError'));
            }
        }

        return redirect()->to('latihan/dataUsers')->with('pesan', 'Users berhasil ditambah.');
    }

    public function editUsers($id)
    {
        $dataUsers = $this->modelUsers->select('email, username, users.id, group_id, auth_groups.name as role, password_hash as password')
            ->join('auth_groups_users', 'auth_groups_users.user_id =  users.id')
            ->join('auth_groups', 'auth_groups.id =  auth_groups_users.group_id')
            ->find($id);

        $dataRole = $this->db->table('auth_groups')->select('*')->get()->getResultArray();
        // dd($dataRole); 

        $data = [
            'title' => 'Edit Users',
            'users' => $dataUsers,
            'role'  => $dataRole
        ];
        return view('latihan/editUsers', $data);
    }

    public function ubahUsers($id)
    {
        $rules = [
            'email' => [
                'rules'  => "required",
                'errors' => [
                    'required' => 'email harus dipilih.',
                ]
            ],
            'username' => [
                'rules'  => "required",
                'errors' => [
                    'required' => 'username harus diisi.'
                ]
            ],
            'group_id' => [
                'rules' => "required",
                'errors' => [
                    'required' => 'role harus dipilih.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $password = $this->request->getVar('password');
        $password_hash = Password::hash($password);

        $data = [
            'id'       => $id,
            'email'    => $this->request->getVar('email'),
            'username' => $this->request->getVar('username'),
            'password_hash' => $password_hash,
        ];
        $this->modelUsers->save($data);

        $role = [
            'user_id'       => $id,
            'group_id' => $this->request->getVar('group_id')
        ];

        $this->db->table('auth_groups_users')->select('*')->where('user_id', $id)->update($role);


        return redirect()->to('latihan/dataUsers')->with('pesan', 'Users berhasil diubah.');
    }

    public function dataBiaya()
    {
        $query = $this->modelBarang->findAll();

        $data = [
            'title' => 'Daftar Harga Barang',
            'data'  => $query
        ];

        return view('latihan/dataBiaya', $data);
    }

    public function tambahBarang()
    {
        $data = [
            'title' => 'Tambah Barang',
        ];

        return view('latihan/tambahBarang', $data);
    }

    public function simpanBarang()
    {
        $rules = [
            'jenis_barang' => [
                'rules'  => 'required|is_unique[tbl_biaya.jenis_barang]',
                'errors' => [
                    'required'  => 'nama barang harus diisi.',
                    'is_unique' => 'barang sudah tersedia'
                ]
            ],
            'harga_barang' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'harga barang harus diisi.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'jenis_barang' => $this->request->getVar('jenis_barang'),
            'harga_barang' => $this->request->getVar('harga_barang'),
        ];

        $this->modelBarang->save($data);
        return redirect()->to('latihan/dataBiaya')->with('pesan', 'Barang berhasil ditambah.');
    }

    public function editBarang($id)
    {
        $dataBarang = $this->modelBarang->find($id);

        $data = [
            'title' => 'Edit Barang',
            'barang' => $dataBarang,
        ];

        return view('latihan/editBarang', $data);
    }

    public function ubahBarang($id)
    {
        $rules = [
            'jenis_barang' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'nama barang harus diisi.',
                ]
            ],
            'harga_barang' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'harga barang harus diisi.',
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id'           => $id,
            'jenis_barang' => $this->request->getVar('jenis_barang'),
            'harga_barang' => $this->request->getVar('harga_barang'),
        ];

        $this->modelBarang->save($data);
        return redirect()->to('latihan/dataBiaya')->with('pesan', 'Barang berhasil diubah.');
    }

    public function hapusBarang($id)
    {
        $this->modelBarang->where('id', $id)->delete();
        return redirect()->back();
    }

    public function dataTransaksi()
    {
        $query = $this->modelTransaksi->select('tbl_transaksi.id, tanggal, no_nota, users.username, jenis_barang, harga_barang, jumlah_barang, total')
            ->join('users', 'users.id = tbl_transaksi.id_user')
            ->join('tbl_biaya', 'tbl_biaya.id = tbl_transaksi.id_biaya')
            ->findAll();

        $data = [
            'title'  => 'Daftar Transaksi Barang',
            'data'   => $query,
        ];

        return view('latihan/dataTransaksi', $data);
    }

    public function tambahTransaksi()
    {
        $users = $this->modelUsers->select('email, username, users.id, group_id, auth_groups.name as role, password_hash as password')
            ->join('auth_groups_users', 'auth_groups_users.user_id =  users.id')
            ->join('auth_groups', 'auth_groups.id =  auth_groups_users.group_id')
            ->where('auth_groups.name', 'user')
            ->findAll();

        $barang = $this->modelBarang->findAll();

        $idMax = $this->modelTransaksi->selectMax('id', 'id')->get()->getRow();
        $kode = $idMax->id;
        $kode++;
        $huruf = 'C';
        $kodeBarang = $huruf . sprintf('%03s', $kode);

        $data = [
            'title'      => 'Tambah Transaksi Barang',
            'kodeBarang' => $kodeBarang,
            'users'      => $users,
            'barang'     => $barang,
        ];

        return view('latihan/tambahTransaksi', $data);
    }

    public function simpanTransaksi()
    {
        $rules = [
            'tanggal' => [
                'rules'  => 'required|is_unique[tbl_transaksi.tanggal]',
                'errors' => [
                    'required'  => 'tanggal barang harus diisi.',
                    'is_unique' => 'tanggal sudah tersedia'
                ]
            ],
            'no_nota' => [
                'rules'  => 'required|is_unique[tbl_transaksi.no_nota]',
                'errors' => [
                    'required'  => 'no_nota barang harus diisi.',
                    'is_unique' => 'no_nota sudah tersedia'
                ]
            ],
            'id_user' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'pelanggan harus diisi.',
                ]
            ],
            'id_biaya' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'nama barang harus diisi.',
                ]
            ],
            'jumlah_barang' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'jumlah barang harus diisi.',
                ]
            ],
            'bayaran' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'bayaran harus diisi.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $harga_barang = $this->request->getVar('harga_barang');
        $jumlah_barang = $this->request->getVar('jumlah_barang');
        $harga = (int) str_replace(["Rp.", "."], "", $harga_barang);

        $data = [
            'tanggal'  => $this->request->getVar('tanggal'),
            'no_nota'  => $this->request->getVar('no_nota'),
            'jumlah_barang'  => $this->request->getVar('jumlah_barang'),
            'id_biaya' => $this->request->getVar('id_biaya'),
            'id_user'  => $this->request->getVar('id_user'),
            'total'    => $harga * $jumlah_barang
        ];

        $this->modelTransaksi->save($data);
        return redirect()->to('latihan/dataTransaksi')->with('pesan', 'Transaksi berhasil ditambah.');
    }

    public function editTransaksi($id)
    {
        $users = $this->modelUsers->select('email, username, users.id, group_id, auth_groups.name as role, password_hash as password')
            ->join('auth_groups_users', 'auth_groups_users.user_id =  users.id')
            ->join('auth_groups', 'auth_groups.id =  auth_groups_users.group_id')
            ->where('auth_groups.name', 'user')
            ->findAll();

        $query = $this->modelTransaksi->select('tbl_transaksi.id, users.id as userid, tbl_biaya.id as barangid, tanggal, no_nota, users.username, jenis_barang, harga_barang, jumlah_barang, total')
            ->join('users', 'users.id = tbl_transaksi.id_user')
            ->join('tbl_biaya', 'tbl_biaya.id = tbl_transaksi.id_biaya')
            ->find($id);

        $barang = $this->modelBarang->findAll();

        $idMax = $this->modelTransaksi->selectMax('id', 'id')->get()->getRow();
        $kode = $idMax->id;
        $kode++;
        $huruf = 'C';
        $kodeBarang = $huruf . sprintf('%03s', $kode);

        $data = [
            'title'      => 'Tambah Transaksi Barang',
            'kodeBarang' => $kodeBarang,
            'users'      => $users,
            'barang'     => $barang,
            'data'       => $query
        ];

        return view('latihan/editTransaksi', $data);
    }

    public function ubahTransaksi($id)
    {
        $rules = [
            'tanggal' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'tanggal barang harus diisi.',
                ]
            ],
            'no_nota' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'no_nota barang harus diisi.',
                ]
            ],
            'id_user' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'pelanggan harus diisi.',
                ]
            ],
            'id_biaya' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'nama barang harus diisi.',
                ]
            ],
            'jumlah_barang' => [
                'rules'  => 'required',
                'errors' => [
                    'required'  => 'jumlah barang harus diisi.',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $harga_barang = $this->request->getVar('harga_barang');
        $jumlah_barang = $this->request->getVar('jumlah_barang');
        $harga = (int) str_replace(["Rp.", "."], "", $harga_barang);

        $data = [
            'id'             => $id,
            'tanggal'        => $this->request->getVar('tanggal'),
            'no_nota'        => $this->request->getVar('no_nota'),
            'jumlah_barang'  => $this->request->getVar('jumlah_barang'),
            'id_biaya'       => $this->request->getVar('id_biaya'),
            'id_user'        => $this->request->getVar('id_user'),
            'total'          => $harga * $jumlah_barang
        ];

        $this->modelTransaksi->save($data);
        return redirect()->to('latihan/dataTransaksi')->with('pesan', 'Transaksi berhasil diubah.');
    }
}
