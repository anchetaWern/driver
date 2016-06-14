<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Googl;
use Carbon\Carbon;

class AdminController extends Controller
{
    private $client;
    private $drive;

    public function __construct(Googl $googl)
    {
   		$this->client = $googl->client();
		$this->client->setAccessToken(session('user.token'));
   		$this->drive = $googl->drive($this->client);
    }


    public function index()
    {
        return view('admin.dashboard');
    }


   	public function files()
   	{
		$result = [];
		$pageToken = NULL;

		$three_months_ago = Carbon::now()->subMonths(3)->toRfc3339String();

		do {
			try {
				$parameters = [
					'q' => "viewedByMeTime >= '$three_months_ago' or modifiedTime >= '$three_months_ago'",
					'orderBy' => 'modifiedTime',
                    'fields' => 'nextPageToken, files(id, name, modifiedTime, iconLink, webViewLink, webContentLink)',
				];

				if ($pageToken) {
					$parameters['pageToken'] = $pageToken;
				}

				$result = $this->drive->files->listFiles($parameters);
                $files = $result->files;

				$pageToken = $result->getNextPageToken();

			} catch (Exception $e) {
			  	return redirect('/files')->with('message',
			  		[
						'type' => 'error',
						'text' => 'Something went wrong while trying to list the files'
			  		]
			  	);
			    $pageToken = NULL;
			}
		} while ($pageToken);

		$page_data = [
			'files' => $files
		];

		return view('admin.files', $page_data);
   }


   	public function search(Request $request)
   	{
   		$query = '';
   		$files = [];

   		if ($request->has('query')) {
	   		$query = $request->input('query');

	   		$parameters = [
	   			'q' => "name contains '$query'",
                'fields' => 'files(id, name, modifiedTime, iconLink, webViewLink, webContentLink)',
	   		];

	   		$result = $this->drive->files->listFiles($parameters);
            if($result){
                $files = $result->files;
            }
   		}

   		$page_data = [
   			'query' => $query,
   			'files' => $files
   		];

   		return view('admin.search', $page_data);
   }


    public function delete($id)
    {
		try {
			$this->drive->files->delete($id);
		} catch (Exception $e) {
			return redirect('/search')
				->with('message', [
					'type' => 'error',
					'text' => 'Something went wrong while trying to delete the file'
				]);
		}

		return redirect('/search')
			->with('message', [
				'type' => 'success',
				'text' => 'File was deleted'
			]);
   }



   	public function upload()
   	{
    	return view('admin.upload');
   	}


   	public function doUpload(Request $request)
    {
		if ($request->hasFile('file')) {

		  	$file = $request->file('file');

			$mime_type = $file->getMimeType();
			$title = $file->getClientOriginalName();
			$description = $request->input('description');

			$drive_file = new \Google_Service_Drive_DriveFile();
			$drive_file->setName($title);
			$drive_file->setDescription($description);
			$drive_file->setMimeType($mime_type);

			try {
				$createdFile = $this->drive->files->create($drive_file, [
					'data' => $file,
					'mimeType' => $mime_type,
					'uploadType' => 'multipart'
				]);

				$file_id = $createdFile->getId();

				return redirect('/upload')
					->with('message', [
						'type' => 'success',
						'text' => "File was uploaded with the following ID: {$file_id}"
				]);

			} catch (Exception $e) {

			    return redirect('/upload')
    				->with('message', [
    					'type' => 'error',
    					'text' => 'An error occured while trying to upload the file'
    				]);

			}
		}

   }


   	public function logout(Request $request)
   	{
   		$request->session()->flush();
   		return redirect('/')->with('message', ['type' => 'success', 'text' => 'You are now logged out']);
   	}

}
