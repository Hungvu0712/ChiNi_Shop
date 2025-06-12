<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductAttachmentController extends Controller
{
    public function destroy($id)
    {
        $attachment = ProductAttachment::findOrFail($id);
        Storage::disk('public')->delete($attachment->attachment_image);
        $attachment->delete();

        return back()->with('success', 'Xóa ảnh đính kèm thành công.');
    }
}
