@extends('layouts.app')

@section('title', $event->title . ' - EventSphere')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 4rem;">
    
    <!-- Left Column: Banner & Info -->
    <div>
        <!-- Event Header & Banner -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 2rem; box-shadow: var(--shadow-soft);">
            <div style="height: 320px; width: 100%; background-image: url('{{ $event->banner_image ? asset($event->banner_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1000&q=80' }}'); background-size: cover; background-position: center; position: relative;">
                <div style="position: absolute; inset: 0; background: linear-gradient(to top, var(--bg-surface), transparent 70%);"></div>
                
                <div style="position: absolute; top: 1.5rem; left: 1.5rem; display: flex; gap: 0.75rem;">
                    <span class="category-badge" style="font-size: 0.85rem;"><i class="fa-solid fa-tag"></i> {{ $event->category->name }}</span>
                    <span class="category-badge" style="background: rgba(139, 92, 246, 0.8); color: #fff;"><i class="fa-solid fa-building-user"></i> {{ $event->organizing_department }}</span>
                </div>

                <div style="position: absolute; bottom: 1.5rem; left: 1.5rem; right: 1.5rem;">
                    <h1 style="font-size: 1.75rem; margin-bottom: 0.35rem; text-shadow: 0 2px 10px rgba(0,0,0,0.8);">{{ $event->title }}</h1>
                    <div style="display: flex; gap: 1.5rem; color: var(--text-muted); font-size: 0.88rem; flex-wrap: wrap;">
                        <span><i class="fa-regular fa-user" style="color: var(--primary);"></i> Organized by {{ $event->organizer->name }}</span>
                        <span><i class="fa-solid fa-star" style="color: var(--warning);"></i> Rating: {{ $event->averageRating() }} / 5.0 ({{ $event->feedbacks->count() }} reviews)</span>
                    </div>
                </div>
            </div>

            <div style="padding: 2rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">About This Event</h3>
                <div style="line-height: 1.7; color: var(--text-main); font-size: 1rem; margin-bottom: 2rem;">
                    {!! nl2br(e($event->description)) !!}
                </div>

                @if($event->rulebook_file)
                    <div style="padding: 1rem 1.25rem; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <i class="fa-solid fa-file-pdf" style="font-size: 1.5rem; color: #ef4444;"></i>
                            <div>
                                <span style="font-weight: 600; font-size: 0.95rem; display: block;">Official Rulebook & Guidelines</span>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">Download event rules, schedule, and submission details</span>
                            </div>
                        </div>
                        <a href="{{ asset($event->rulebook_file) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="fa-solid fa-download"></i> Download PDF</a>
                    </div>
                @endif

                <!-- Social Media Sharing & Calendar Integration -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                    <div>
                        <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Share Event</span>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="shareEvent('whatsapp', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm" style="background: #25D366; color: #fff;"><i class="fa-brands fa-whatsapp"></i></button>
                            <button onclick="shareEvent('twitter', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm" style="background: #1DA1F2; color: #fff;"><i class="fa-brands fa-x-twitter"></i></button>
                            <button onclick="shareEvent('facebook', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm" style="background: #4267B2; color: #fff;"><i class="fa-brands fa-facebook-f"></i></button>
                            <button onclick="shareEvent('linkedin', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm" style="background: #0077b5; color: #fff;"><i class="fa-brands fa-linkedin-in"></i></button>
                            <button onclick="shareEvent('email', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm btn-secondary"><i class="fa-solid fa-envelope"></i></button>
                        </div>
                    </div>

                    <div>
                        <span style="font-weight: 600; font-size: 0.9rem; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Sync Calendar</span>
                        <a href="{{ route('events.calendar', $event->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fa-regular fa-calendar-plus" style="color: var(--primary);"></i> Add to Calendar (.ics)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback & Reviews Section -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-soft);">
            <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Participant Ratings & Reviews</h3>

            @auth
                @if(isset($userRegistration) && in_array($userRegistration->status, ['registered', 'attended']))
                    <div style="background: var(--bg-card); border-radius: var(--radius-md); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
                        <h4 style="font-size: 1.05rem; margin-bottom: 1rem;">Leave Your Feedback for Organizers</h4>
                        <form action="{{ route('events.feedback', $event->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" style="font-size: 0.85rem;">Your Role Title</label>
                                <select name="user_role_title" class="form-select" required>
                                    <option value="Student Participant">Student Participant</option>
                                    <option value="Student Attendee / Viewer">Student Attendee / Viewer</option>
                                    <option value="Event Volunteer">Event Volunteer</option>
                                    <option value="Faculty Delegate">Faculty Delegate</option>
                                </select>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                                <div>
                                    <label class="form-label" style="font-size: 0.8rem;">Overall</label>
                                    <select name="overall_rating" class="form-select">
                                        <option value="5">5 ★★★★★</option>
                                        <option value="4">4 ★★★★☆</option>
                                        <option value="3">3 ★★★☆☆</option>
                                        <option value="2">2 ★★☆☆☆</option>
                                        <option value="1">1 ★☆☆☆☆</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 0.8rem;">Venue</label>
                                    <select name="venue_rating" class="form-select">
                                        <option value="5">5 ★★★★★</option>
                                        <option value="4">4 ★★★★☆</option>
                                        <option value="3">3 ★★★☆☆</option>
                                        <option value="2">2 ★★☆☆☆</option>
                                        <option value="1">1 ★☆☆☆☆</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 0.8rem;">Coordination</label>
                                    <select name="coordination_rating" class="form-select">
                                        <option value="5">5 ★★★★★</option>
                                        <option value="4">4 ★★★★☆</option>
                                        <option value="3">3 ★★★☆☆</option>
                                        <option value="2">2 ★★☆☆☆</option>
                                        <option value="1">1 ★☆☆☆☆</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 0.8rem;">Tech Setup</label>
                                    <select name="tech_rating" class="form-select">
                                        <option value="5">5 ★★★★★</option>
                                        <option value="4">4 ★★★★☆</option>
                                        <option value="3">3 ★★★☆☆</option>
                                        <option value="2">2 ★★☆☆☆</option>
                                        <option value="1">1 ★☆☆☆☆</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 0.8rem;">Hospitality</label>
                                    <select name="hospitality_rating" class="form-select">
                                        <option value="5">5 ★★★★★</option>
                                        <option value="4">4 ★★★★☆</option>
                                        <option value="3">3 ★★★☆☆</option>
                                        <option value="2">2 ★★☆☆☆</option>
                                        <option value="1">1 ★☆☆☆☆</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" style="font-size: 0.85rem;">Comments / Suggestions</label>
                                <textarea name="comments" class="form-control" rows="3" placeholder="Share your experience or suggestions for future events..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i> Submit Feedback</button>
                        </form>
                    </div>
                @endif
            @endauth

            @if($event->feedbacks->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($event->feedbacks as $fb)
                        <div style="background: var(--bg-card); padding: 1rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <div>
                                    <span style="font-weight: 700; color: var(--text-main);">{{ $fb->user->name ?? 'Anonymous Student' }}</span>
                                    <span style="font-size: 0.75rem; color: var(--text-muted); margin-left: 0.5rem;">({{ $fb->user_role_title }})</span>
                                </div>
                                <div style="color: var(--warning); font-size: 0.9rem;">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="fa-{{ $i <= $fb->overall_rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($fb->comments)
                                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5;">{{ $fb->comments }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--text-muted); font-size: 0.9rem; text-align: center; padding: 1.5rem;">No reviews submitted yet for this event.</p>
            @endif
        </div>
    </div>

    <!-- Right Column: Registration Card & Capacity Tracker -->
    <div>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2rem; position: sticky; top: 100px; box-shadow: var(--shadow-soft);">
            
            <div style="margin-bottom: 1.5rem;">
                <div style="font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.3rem;">Real-Time Availability</div>
                <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                    @if($event->isFull())
                        <span style="font-size: 2rem; font-weight: 800; color: #f87171;">FULL</span>
                        <span style="color: var(--warning); font-size: 0.9rem; font-weight: 600;">(Waitlist Open)</span>
                    @else
                        <span style="font-size: 2.25rem; font-weight: 800; color: #34d399;">{{ $event->available_slots }}</span>
                        <span style="color: var(--text-muted); font-size: 0.9rem;">slots remaining out of {{ $event->capacity }}</span>
                    @endif
                </div>
            </div>

            <!-- Seating Progress Bar -->
            <div style="margin-bottom: 2rem;">
                <div class="progress-bar-bg" style="height: 10px;">
                    <div class="progress-bar-fill" style="width: {{ (($event->capacity - $event->available_slots) / max(1, $event->capacity)) * 100 }}%;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.78rem; color: var(--text-muted); margin-top: 0.4rem;">
                    <span>{{ $event->capacity - $event->available_slots }} Registered</span>
                    <span>Max Seating: {{ $event->capacity }}</span>
                </div>
            </div>

            <!-- Event Dates & Location Card -->
            <div style="display: flex; flex-direction: column; gap: 1rem; background: var(--bg-card); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 2rem; font-size: 0.9rem;">
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;"><i class="fa-regular fa-clock" style="color: var(--primary);"></i> Start Date & Time</span>
                    <strong style="color: var(--text-main);">{{ $event->start_date->format('l, F j, Y • h:i A') }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;"><i class="fa-solid fa-flag-checkered" style="color: var(--secondary);"></i> End Date & Time</span>
                    <strong style="color: var(--text-main);">{{ $event->end_date->format('l, F j, Y • h:i A') }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;"><i class="fa-solid fa-location-dot" style="color: var(--accent);"></i> Venue / Hall</span>
                    <strong style="color: var(--text-main);">{{ $event->venue }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 0.8rem;"><i class="fa-solid fa-hourglass-end" style="color: var(--warning);"></i> Registration Deadline</span>
                    <strong style="color: var(--text-main);">{{ $event->registration_deadline->format('M d, Y • h:i A') }}</strong>
                </div>
            </div>

            <!-- Registration Action Buttons -->
            @auth
                @if(isset($userRegistration))
                    @if($userRegistration->status === 'registered')
                        <div style="padding: 1rem; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius-md); text-align: center; margin-bottom: 1rem;">
                            <i class="fa-solid fa-circle-check" style="color: #34d399; font-size: 1.5rem; margin-bottom: 0.3rem;"></i>
                            <h4 style="color: #34d399; font-size: 1rem;">You are Registered!</h4>
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">View your QR check-in token in your student dashboard.</p>
                        </div>
                    @elseif($userRegistration->status === 'waitlisted')
                        <div style="padding: 1rem; background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius-md); text-align: center; margin-bottom: 1rem;">
                            <i class="fa-solid fa-clock-rotate-left" style="color: #fbbf24; font-size: 1.5rem; margin-bottom: 0.3rem;"></i>
                            <h4 style="color: #fbbf24; font-size: 1rem;">On Waitlist</h4>
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">You will be auto-promoted if a registered participant cancels.</p>
                        </div>
                    @endif

                    <form action="{{ route('events.register.cancel', $userRegistration->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;" onclick="return confirm('Are you sure you want to cancel your registration?');">
                            <i class="fa-solid fa-xmark"></i> Cancel Registration
                        </button>
                    </form>
                @else
                    <form action="{{ route('events.register', $event->id) }}" method="POST">
                        @csrf
                        @if($event->isFull())
                            <button type="submit" class="btn btn-primary" style="width: 100%; background: linear-gradient(135deg, var(--warning), #d97706);">
                                <i class="fa-solid fa-user-plus"></i> Join Waitlist
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                <i class="fa-solid fa-user-check"></i> Register Now
                            </button>
                        @endif
                    </form>
                @endif

                <form action="{{ route('events.bookmark', $event->id) }}" method="POST" style="margin-top: 0.75rem;">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%;">
                        <i class="fa-{{ $isBookmarked ? 'solid' : 'regular' }} fa-bookmark" style="color: var(--primary);"></i>
                        {{ $isBookmarked ? 'Bookmarked in Profile' : 'Bookmark Event' }}
                    </button>
                </form>
            @else
                <div style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: var(--radius-md); padding: 1.25rem; text-align: center;">
                    <p style="font-weight: 600; font-size: 0.95rem; margin-bottom: 0.75rem; color: var(--text-main);">Want to register or join waitlist for this event?</p>
                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Log In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Create Account</a>
                    </div>
                </div>
            @endauth

        </div>
    </div>
</div>
@endsection
