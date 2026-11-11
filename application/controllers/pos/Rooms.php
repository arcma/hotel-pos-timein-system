<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rooms extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Rooms_model');
        $this->load->model('Room_image_model');
    }

    public function index()
    {
        $data['rooms'] = $this->Rooms_model->getall();
		$data['title'] = 'rooms';

        $this->load->view('templates/top', $data);
		$this->load->view('pos/rooms/list', $data);
		$this->load->view('templates/bottom');
    }
	
    public function add()
    {
		$data['title'] = 'rooms';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/rooms/add', $data);
		$this->load->view('templates/bottom');
    }

    public function do_add(){

        if(isset($_POST)){
			$postData = $_POST;
			$products = $this->Rooms_model->do_add($postData);
			if($products){
				$this->session->set_flashdata('success', 'Room has been created successfully. You can now upload room images.');
				redirect('pos/rooms/edit/'.$products);
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to create room');
		redirect("pos/rooms");
    }

    public function edit($id = false){
		if($id){
			$room = $this->Rooms_model->get($id);
            $data['title'] = 'rooms';
            if (!$room) { show_404(); }
            $data['room'] = $room;
            $data['room_images'] = $this->Room_image_model->getByRoom($id);
            $this->load->view('templates/top', $data);
            $this->load->view('pos/rooms/edit', $data);
            $this->load->view('templates/bottom');
		}else{
			$this->session->set_flashdata('error', 'Failed to load room missing id');
            redirect("pos/rooms");
		}
        
	}
	
	public function do_edit($id){
		
		
		if(isset($_POST)){
			$postData = $_POST;
			$products = $this->Rooms_model->do_edit($postData, $id);
			if($products){
				$this->session->set_flashdata('success', 'Room '.$_POST['title'].' has been updated successfully');
				redirect("pos/rooms");
				
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to update room');
		redirect("pos/rooms");
	}

    public function disable($id){
		if($id){
			$products = $this->Rooms_model->do_disable($id);
			$this->session->set_flashdata('success', 'Room successfully disabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to disabled room');
		}
		redirect("pos/rooms");
	}
	
	public function enable($id){
		if($id){
			$products = $this->Rooms_model->do_enable($id);
			$this->session->set_flashdata('success', 'Room successfully enabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to enabled room');
		}
		redirect("pos/rooms");
	}

    /**
     * AJAX endpoint: POST one or more files in images[] for a room.
     * URL: /pos/rooms/upload_images/{room_id}
     */
    public function upload_images($roomID = 0)
    {
        $roomID = (int)$roomID;
        if ($roomID < 1 || !$this->Rooms_model->get($roomID)) {
            return $this->json_response(array('success' => false, 'message' => 'Room not found.'), 404);
        }
        if (strtoupper($this->input->method()) !== 'POST') {
            return $this->json_response(array('success' => false, 'message' => 'POST request required.'), 405);
        }
        if (empty($_FILES['images']) || empty($_FILES['images']['name']) || !is_array($_FILES['images']['name'])) {
            return $this->json_response(array('success' => false, 'message' => 'Please select one or more images.'), 400);
        }

        $uploadDir = FCPATH . 'assets/uploads/rooms/images/';
        if (!is_dir($uploadDir) && !@mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            return $this->json_response(array('success' => false, 'message' => 'Upload directory could not be created.'), 500);
        }
        if (!is_writable($uploadDir)) {
            return $this->json_response(array('success' => false, 'message' => 'Upload directory is not writable.'), 500);
        }

        $saved = array();
        $errors = array();
        $count = count($_FILES['images']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
            if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                $errors[] = $_FILES['images']['name'][$i] . ': upload error.';
                continue;
            }
            if ($_FILES['images']['size'][$i] > 8 * 1024 * 1024) {
                $errors[] = $_FILES['images']['name'][$i] . ': exceeds the 8 MB limit.';
                continue;
            }
            $tmp = $_FILES['images']['tmp_name'][$i];
            $mime = function_exists('finfo_open') ? finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tmp) : '';
            $allowed = array('image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp');
            if (!isset($allowed[$mime]) || @getimagesize($tmp) === false) {
                $errors[] = $_FILES['images']['name'][$i] . ': only JPG, PNG, GIF, and WEBP images are allowed.';
                continue;
            }
            $fileName = 'room_' . $roomID . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(5)) . '.' . $allowed[$mime];
            if (!move_uploaded_file($tmp, $uploadDir . $fileName)) {
                $errors[] = $_FILES['images']['name'][$i] . ': could not save file.';
                continue;
            }
            $relativePath = 'assets/uploads/rooms/images/' . $fileName;
            $imageID = $this->Room_image_model->create($roomID, $relativePath);
            if (!$imageID) {
                @unlink($uploadDir . $fileName);
                $errors[] = $_FILES['images']['name'][$i] . ': could not save image record to database.';
                continue;
            }
            $saved[] = array('id' => (int)$imageID, 'name' => $fileName, 'path' => $relativePath, 'status' => 1);
        }

        if (count($saved) === 0) {
            return $this->json_response(array('success' => false, 'message' => $errors ? implode(' ', $errors) : 'No images were uploaded.', 'images' => array()), 400);
        }
        $message = count($saved) . ' image(s) uploaded successfully.';
        if ($errors) $message .= ' Some files were skipped: ' . implode(' ', $errors);
        return $this->json_response(array('success' => true, 'message' => $message, 'images' => $saved), 200);
    }

    /** AJAX endpoint: POST status=0|1 to /pos/rooms/image_status/{image_id}. */
    public function image_status($imageID = 0)
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            return $this->json_response(array('success' => false, 'message' => 'POST request required.'), 405);
        }
        $image = $this->Room_image_model->get((int)$imageID);
        if (!$image) return $this->json_response(array('success' => false, 'message' => 'Image not found.'), 404);
        $status = $this->input->post('status');
        if (!in_array((string)$status, array('0', '1'), true)) {
            return $this->json_response(array('success' => false, 'message' => 'Invalid image status.'), 400);
        }
        $ok = $this->Room_image_model->setStatus($imageID, (int)$status);
        return $this->json_response(array('success' => (bool)$ok, 'message' => $ok ? 'Image status updated.' : 'Unable to update image status.'), $ok ? 200 : 500);
    }

    /** AJAX endpoint: POST to /pos/rooms/delete_image/{image_id}. */
    public function delete_image($imageID = 0)
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            return $this->json_response(array('success' => false, 'message' => 'POST request required.'), 405);
        }
        $image = $this->Room_image_model->get((int)$imageID);
        if (!$image) return $this->json_response(array('success' => false, 'message' => 'Image not found.'), 404);
        $ok = $this->Room_image_model->deleteImage($imageID);
        return $this->json_response(array('success' => (bool)$ok, 'message' => $ok ? 'Image deleted.' : 'Unable to delete image.'), $ok ? 200 : 500);
    }

    private function json_response($payload, $statusCode = 200)
    {
        return $this->output
            ->set_status_header($statusCode)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload));
    }

	public function delete($id){
		if ((int)$id > 0 && $this->Rooms_model->do_delete($id)) {
			$this->session->set_flashdata('success', 'Room and its linked table successfully deleted');
		}else{
			$this->session->set_flashdata('error', 'Failed to delete room and its linked table');
		}
		redirect("pos/rooms");
	}
	

}
