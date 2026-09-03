@extends('layouts.app')

@section('title', 'Media Gallery - EventSphere')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.85rem; margin-bottom: 0.35rem;">Campus Media Gallery</h1>
        <p style="color: var(--text-muted); font-size: 0.92rem;">Explore photos and videos from past campus fests, workshops, and sports tournaments</p>
    </div>

    @auth
        @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
            <button data-modal-target="uploadMediaModal" class="btn btn-primary">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Media
            </button>
        @endif
    @endauth
</div>

<!-- Category & Department Filters -->
<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.25rem; margin-bottom: 2.5rem;">
    <form action="{{ route('gallery.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
        <div style="flex: 1; min-width: 200px;">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 180px;">
            <select name="department" class="form-select">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 140px;">
            <select name="type" class="form-select">
                <option value="">All Media Types</option>
                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Videos</option>
            </select>
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('gallery.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
        </div>
    </form>
</div>

<!-- Gallery Grid -->
@if($mediaItems->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        @foreach($mediaItems as $item)
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; position: relative;" class="gallery-card">
                <div style="height: 220px; width: 100%; position: relative; background: var(--bg-card); overflow: hidden;">
                    @if($item->media_type === 'video')
                        <video src="{{ $item->file_url }}" controls style="width: 100%; height: 100%; object-fit: cover;"></video>
                    @else
                        <img src="{{ $item->file_url }}" alt="{{ $item->title }}" onerror="this.onerror=null;this.src='{{ $item->getFallbackImage() }}';" style="width: 100%; height: 100%; object-fit: cover; transition: var(--transition);">
                    @endif

                    <span class="category-badge" style="top: 0.75rem; left: 0.75rem; font-size: 0.75rem;">{{ $item->category }}</span>

                    @auth
                        <form action="{{ route('gallery.favorite', $item->id) }}" method="POST" style="position: absolute; top: 0.75rem; right: 0.75rem; z-index: 10;">
                            @csrf
                            <button type="submit" class="btn btn-sm" style="background: rgba(11, 15, 25, 0.75); backdrop-filter: blur(4px); border: 1px solid var(--border-color); color: {{ in_array($item->id, $savedMediaIds) ? '#ef4444' : '#ffffff' }}; width: 36px; height: 36px; padding: 0; border-radius: 50%;">
                                <i class="fa-{{ in_array($item->id, $savedMediaIds) ? 'solid' : 'regular' }} fa-heart"></i>
                            </button>
                        </form>
                    @endauth
                </div>

                <div style="padding: 1rem;">
                    <h4 style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $item->title }}</h4>
                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted);">
                        <span>{{ $item->department ?? 'Campus Life' }}</span>
                        <span>{{ $item->year }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 3rem;">
        {{ $mediaItems->links() }}
    </div>
@else
    <div style="text-align: center; padding: 5rem 2rem; background: var(--bg-surface); border: 1px dashed var(--border-color); border-radius: var(--radius-lg);">
        <i class="fa-solid fa-images" style="font-size: 3rem; color: var(--text-dim); margin-bottom: 1rem;"></i>
        <h3 style="color: var(--text-muted);">Media Gallery Empty</h3>
        <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 0.5rem;">Organizers can upload event photos and videos from their portal!</p>
    </div>
@endif

<!-- Upload Media Modal (Organizers & Admins) -->
@auth
    @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
        <div id="uploadMediaModal" class="modal-backdrop">
            <div class="modal-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <h3 style="font-size: 1.25rem;"><i class="fa-solid fa-cloud-arrow-up" style="color: var(--primary);"></i> Upload Media to Gallery</h3>
                    <button data-modal-close style="background: none; border: none; color: var(--text-muted); font-size: 1.25rem; cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form action="{{ route('gallery.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Media Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="Annual Fest 2026 Opening Performance" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Media Type *</label>
                            <select name="media_type" class="form-select" required>
                                <option value="image">Image (JPG, PNG, WEBP)</option>
                                <option value="video">Video (MP4, MOV)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Organizing Department</label>
                            <input type="text" name="department" class="form-control" placeholder="Computer Science & Eng">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Year *</label>
                            <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Select File *</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                        <button type="button" data-modal-close class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Start Upload</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endauth
@endsection
