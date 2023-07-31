<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PegawaiController extends ResourceController
{
    protected $modelName = 'App\Models\Pegawai';
    protected $format = 'json';
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $data = [
            'message' => 'success',
            'data_pegawai' => $this->model->findAll()
        ];

        return $this->respond($data, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = [
            'message' => 'success',
            'pegawai_byid' => $this->model->find($id)
        ];

        if ($data['pegawai_byid'] == null) {
            return $this->failNotFound('Data Pegawai Tidak Ditemukan.');
        }

        return $this->respond($data, 200);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = $this->validate([
            'nama' => 'required',
            'nik' => 'required',
            'jabatan' => 'required',
            'departemen' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'foto' => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto, image/jpg,image/jpeg,image/png]'
        ]);

        if (!$rules) {
            $response = [
                'message' => $this->validator->getErrors()
            ];

            return $this->failValidationErrors($response);
        }

        $foto = $this->request->getFile('foto');
        $namaFoto = $foto->getRandomName();
        $foto->move('foto', $namaFoto);

        $this->model->insert([
            'nama' => esc($this->request->getVar('nama')),
            'nik' => esc($this->request->getVar('nik')),
            'jabatan' => esc($this->request->getVar('jabatan')),
            'departemen' => esc($this->request->getVar('departemen')),
            'email' => esc($this->request->getVar('email')),
            'alamat' => esc($this->request->getVar('alamat')),
            'foto' => $namaFoto
        ]);

        $response = [
            'message' => 'Data Pegawai Berhasil Ditambahkan'
        ];

        return $this->respondCreated($response);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $rules = $this->validate([
            'nama' => 'required',
            'nik' => 'required',
            'jabatan' => 'required',
            'departemen' => 'required',
            'email' => 'required',
            'alamat' => 'required',
            'foto' => 'max_size[foto,2048]|is_image[foto]|mime_in[foto, image/jpg,image/jpeg,image/png]'
        ]);

        if (!$rules) {
            $response = [
                'message' => $this->validator->getErrors()
            ];

            return $this->failValidationErrors($response);
        }

        $foto = $this->request->getFile('foto');

        if ($foto) {
            $namaFoto = $foto->getRandomName();
            $foto->move('foto', $namaFoto);
            unlink('foto/' . $this->request->getPost('fotoLama'));
        } else {
            $namaFoto = $this->request->getPost('fotoLama');
        }

        $namaFoto = $foto->getRandomName();
        $foto->move('foto', $namaFoto);

        $this->model->update($id, [
            'nama' => esc($this->request->getVar('nama')),
            'nik' => esc($this->request->getVar('nik')),
            'jabatan' => esc($this->request->getVar('jabatan')),
            'departemen' => esc($this->request->getVar('departemen')),
            'email' => esc($this->request->getVar('email')),
            'alamat' => esc($this->request->getVar('alamat')),
        ]);

        $response = [
            'message' => 'Data Pegawai Berhasil Diubah.'
        ];

        return $this->respond($response, 200);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $this->model->delete($id);

        $response = [
            'message' => 'Data Pegawai Berhasil Dihapus.'
        ];

        return $this->respondDeleted($response);
    }
}
