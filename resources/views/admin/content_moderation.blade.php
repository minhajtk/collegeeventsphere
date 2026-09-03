@extends('layouts.portal')

@section('title', 'Content Moderation - Admin Portal')

@section('content')
<div class="portal-topbar">
    <div>
        <div class="portal-breadcrumbs">
            <span>Admin Portal</span>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.65rem;"></i>
            <span style="color: #ffffff; font-weight: 600;">Content Moderation</span>
        </div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.2rem;"><i class="fa-solid fa-shield-halved" style="color: var(--secondary);"></i> Content Moderation Hub</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; font-family: var(--font-sub);">
            Review student ratings, feedback comments, and gallery media to maintain campus guidelines
        </p>
    </div>
</div>

<!-- Moderation Tables -->
<div class="portal-card">
    <div class="portal-card-header">
        <h3 style="font-size: 1.15rem;"><i class="fa-regular fa-comment-dots" style="color: var(--accent);"></i> Student Feedback Entries ({{ $feedbacks->total() }})</h3>
    </div>

    @if($feedbacks->count() > 0)
        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>User</th>
                        <th>Role Title</th>
                        <th>Rating</th>
                        <th>Comments</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacks as $fb)
                        <tr>
                            <td><strong>{{ $fb->event->title ?? 'Deleted Event' }}</strong></td>
                            <td style="font-size: 0.85rem;">{{ $fb->user->name ?? 'User' }}</td>
                            <td style="font-size: 0.82rem; color: var(--text-muted);">{{ $fb->user_role_title }}</td>
                            <td><span style="color: var(--warning); font-weight: 700;">{{ $fb->overall_rating }} ★</span></td>
                            <td style="max-width: 320px; font-size: 0.85rem; color: var(--text-main);">{{ $fb->comments ?? '-' }}</td>
                            <td>
                                <form action="{{ route('admin.content.feedback.delete', $fb->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" onclick="return confirm('Remove this feedback entry?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1.25rem;">
            {{ $feedbacks->links() }}
        </div>
    @else
        <p style="color: var(--text-muted); text-align: center; padding: 1.5rem; font-family: var(--font-sub); font-size: 0.88rem;">No feedback entries to display.</p>
    @endif
</div>

<div class="portal-card">
    <div class="portal-card-header">
        <h3 style="font-size: 1.15rem;"><i class="fa-solid fa-photo-film" style="color: var(--secondary);"></i> Media Gallery Uploads ({{ $mediaList->total() }})</h3>
    </div>

    @if($mediaList->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem;">
            @foreach($mediaList as $media)
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
                    <div style="height: 140px; background: #000; overflow: hidden; position: relative;">
                        @if($media->media_type === 'video')
                            <video src="{{ $media->file_url }}" style="width: 100%; height: 100%; object-fit: cover;"></video>
                        @else
                            <img src="{{ $media->file_url }}" alt="{{ $media->title }}" onerror="this.onerror=null;this.src='{{ $media->getFallbackImage() }}';" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    <div style="padding: 0.85rem;">
                        <h4 style="font-size: 0.9rem; margin-bottom: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $media->title }}</h4>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.75rem;">By {{ $media->uploader->name ?? 'User' }}</p>

                        <form action="{{ route('admin.content.media.delete', $media->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" style="width: 100%; padding: 0.35rem; font-size: 0.78rem;" onclick="return confirm('Delete this media file?');">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="margin-top: 1.25rem;">
            {{ $mediaList->links() }}
        </div>
    @else
        <p style="color: var(--text-muted); text-align: center; padding: 1.5rem; font-family: var(--font-sub); font-size: 0.88rem;">No media uploads to display.</p>
    @endif
</div>
@endsection
