<?php

class NoteShare
{
    private string $otherUserUUID;
    private string $title;
    private string $noteUUID;

    /**
     * @param string $otherUserUUID
     * @param string $title
     * @param string $noteUUID
     */
    public function __construct(string $otherUserUUID, string $title, string $noteUUID)
    {
        $this->otherUserUUID = $otherUserUUID;
        $this->title = $title;
        $this->noteUUID = $noteUUID;
    }

    /**
     * @return string
     */
    public function getOtherUserUUID(): string
    {
        return $this->otherUserUUID;
    }

    /**
     * @param string $otherUserUUID
     */
    public function setOtherUserUUID(string $otherUserUUID): void
    {
        $this->otherUserUUID = $otherUserUUID;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getNoteUUID(): string
    {
        return $this->noteUUID;
    }

    /**
     * @param string $noteUUID
     */
    public function setNoteUUID(string $noteUUID): void
    {
        $this->noteUUID = $noteUUID;
    }


}