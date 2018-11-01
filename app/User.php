<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'major', 'semester', 'bio', 'confirmation_code'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Returns the major this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function major()
    {
        return $this->belongsTo('App\Major');
    }

    /**
     * Return list of questions asked by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany('App\Question', 'asker_id');
    }


    /**
     * Return list of questions asked by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lastFiveQuestions()
    {
        return $this->questions()->orderBy('id', 'desc')->take(5)->get();
    }

    /**
     * Return list of answers posted by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany('App\Answer', 'responder_id');
    }

    /**
     * Return the last 5  answers posted by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function lastFiveAnswers()
    {
        return $this->answers()->orderBy('id', 'desc')->take(5)->get();
    }


    /**
     * Get a list of notifications of this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    /**
     * Get a list of unseen notifications of this user.
     * @return mixed
     */
    public function new_notifications()
    {
        return $this->notifications()->where('seen', '=', 0);
    }


    /**
     * Votes on answers done by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answer_votes()
    {
        return $this->hasMany('App\AnswerVote');
    }

    public function upvotesOnAnswer($id)
    {
        return $this->hasMany('App\AnswerVote')->where('type', '=', 0)->where('answer_id', '=', $id)->get();
    }

    public function downvotesOnAnswer($id)
    {
        return $this->hasMany('App\AnswerVote')->where('type', '=', 1)->where('answer_id', '=', $id)->get();
    }


    /**
     * Votes on questions done by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function question_votes()
    {
        return $this->hasMany('App\QuestionVote');
    }

    public function upvotesOnQuestion($id)
    {
        return $this->hasMany('App\QuestionVote')->where('type', '=', 0)->where('question_id', '=', $id)->get();
    }

    public function downvotesOnQuestion($id)
    {
        return $this->hasMany('App\QuestionVote')->where('type', '=', 1)->where('question_id', '=', $id)->get();
    }


    /**
     * Return a list of courses subscribed by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscribed_courses()
    {
        return $this->belongsToMany('App\Course', 'subscribe');
    }


    /**
     * Subscribe to a certain course
     * @param $course_id
     */
    public function subscribe_to_course($course_id)
    {
        $this->subscribed_courses()->attach($course_id);
    }


    /**
     * Subscribe to multiple courses
     * @param $ids
     */
    public function subscribe_to_courses($ids)
    {
        $this->subscribed_courses()->attach($ids);
    }


    /**
     * Subscribe to a whole major
     * @param $major_id
     */
    public function subscribe_to_major($major_id)
    {
        $major = Major::find($major_id);
        $courses = $major->courses();
        foreach ($courses as $course) {
            $this->subscribe_to_course($course->id);
        }
    }


    /**
     * returns list of questions to appear in the homepage
     *
     */
    public function home_questions()
    {
        $courses = $this->subscribed_courses()->get(['courses.id']);
        $courses_ids = array();
        foreach ($courses as $course) {
            $courses_ids[] = $course->id;
        }
        $questions = Question::whereIn('course_id', $courses_ids);
        return $questions;
    }


    /**
     * Upvote or downvote a question.
     * @param $question_id
     * @param $type
     */

    public function vote_on_question($question_id, $type)
    {
        $questionVote = new QuestionVote();
        $questionVote->user_id = $this->id;
        $questionVote->type = $type;
        $questionVote->question_id = $question_id;
        $questionVote->save();
    }


    /**
     * Upvote or downvote an answer.
     * @param $answer_id
     * @param $type
     */
    public function vote_on_answer($answer_id, $type)
    {
        $answerVote = new AnswerVote();
        $answerVote->user_id = $this->id;
        $answerVote->type = $type;
        $answerVote->answer_id = $answer_id;
        $answerVote->save();
    }

    /**
     * Returns a list of reports on questions submitted by this user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function question_reports()
    {
        return $this->hasMany('App\QuestionReport');
    }

    /**
     * Returns a list of reports on answers submitted by this user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answer_reports()
    {
        return $this->hasMany('App\AnswerReport');
    }


    /**
     * Return a list of mails received from admins by this user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function received_mails()
    {
        return $this->belongsToMany('App\AdminMail', 'mail_recipients', 'user_id', 'mail_id');
    }


    /*
     * Admin roles:
     * - Create major
     * - Create course
     * - Delete major
     * - Delete course
     * - Delete question
     * - Delete answer
     */


    public function create_major($major_name, $faculty_name)
    {
        if ($this->role != 1)
            return;
        $major = new Major();
        $major->major = $major_name;
        $major->faculty = $faculty_name;
        $major->save();
    }


    public function create_course($course_code1, $course_name, $course_code)

    {
        if ($this->role != 1)
            return;
        $course = new Course();
        $course->course_name = $course_name;
        $course->course_code = $course_code1;
        $course->semester = $course_code;
        $course->save();
    }


    public function delete_major($major_id)
    {
        if ($this->role != 1)
            return;
        $major = Major::find($major_id);
        $major->delete();
    }

    public function delete_course($course_id)
    {
        if ($this->role != 1)
            return;
        $course = Course::find($course_id);
        $course->delete();
    }


    public function delete_question($question_id)
    {
        if ($this->role != 1)
            return;
        $question = Question::find($question_id);
        $question->delete();
    }

    public function delete_answer($answer_id)
    {
        if ($this->role != 1)
            return;
        $answer = Answer::find($answer_id);
        $answer->delete();
    }


    /**
     * Returns a list of mails sent by this admin
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sent_mails()
    {
        return $this->hasMany('App\AdminMail', 'user_id');
    }

    /**
     * Calender relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calender()
    {
        return $this->hasMany('App\Calender');
    }

    public function createdEvents()
    {
        return $this->hasMany('App\Event');
    }

    public function announcements()
    {
        return $this->hasMany('App\Announcement');
    }


    /**
     * Votes on notes done by this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function note_votes()
    {
        return $this->hasMany('App\NoteVote');
    }

    public function upvotesOnNote($id)
    {
        return $this->hasMany('App\NoteVote')->where('type', '=', 0)->where('note_id', '=', $id)->get();
    }

    public function downvotesOnNote($id)
    {
        return $this->hasMany('App\NoteVote')->where('type', '=', 1)->where('note_id', '=', $id)->get();
    }

    /**
     * Upvote or downvote a note.
     * @param $note_id
     * @param $type
     */

    public function vote_on_note($note_id, $type)
    {
        $noteVote = new NoteVote();
        $noteVote->user_id = $this->id;
        $noteVote->type = $type;
        $noteVote->note_id = $note_id;
        $noteVote->save();
    }

    public function storeReviews()
    {
        return $this->hasMany('App\Review');
    }

    public function bookmarked_questions()
    {
        return $this->hasMany('App\BookmarkQuestion', 'user_id');
    }

    public function bookmarked_components_questions()
    {
        return $this->hasMany('App\BookmarkComponentQuestion', 'user_id');
    }

}
