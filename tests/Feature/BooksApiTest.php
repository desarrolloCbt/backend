<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books(){
        $books = Book::factory(4)->create();
        // dd($books->count());
        // $this->get('/api/books')->dump();
        // dd(route('books.index'));
        $this->getJson(route('books.index'))->assertJsonFragment([
            'title'=>$books[0]->title
        ])->assertJsonFragment([
            'title'=>$books[1]->title
        ]);
    }

    /** @test */
    function can_get_one_book(){
        $book = Book::factory()->create();
        $this->getJson(route('books.show',$book))->assertJsonFragment([
            'title'=>$book->title
        ]);
    }

    /** @test */
    function can_create_book(){
        $this->postJson(route('books.store'),[])
        ->assertJsonValidationErrorFor('title');
        $this->postJson(route('books.store'),[
            'title'=>'nuevo Libro'
        ])->assertJsonFragment([
            'title'=>'nuevo Libro'
        ]);
        $this->assertDatabaseHas('books',[
            'title'=>'nuevo Libro'
        ]);
    }

    /** @test */
    function can_update_books(){
        $book=Book::factory()->create();

        $this->patchJson(route('books.update',$book),[])
        ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update',$book),['title'=>'Editado'])
        ->assertJsonFragment([
            'title'=>'Editado'
        ]);

        $this->assertDatabaseHas('books',[
            'title'=>'Editado'
        ]);
    }

    /** @test */
    function can_delete_books(){
        $book=Book::factory()->create();
        $this->deleteJson(route('books.destroy',$book))
        ->assertNoContent();
        $this->assertDatabaseCount('books',0);
    }


}
