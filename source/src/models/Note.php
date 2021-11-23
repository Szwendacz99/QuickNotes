<?php

class Note{
    private string $title;
    private string $text;
    private DateTime $timeCreated;
    private DateTime $timeLastEdit;

    /**
     * @param string $title
     * @param string $text
     */
    public function __construct(string $title, string $text)
    {
        $this->title = $title;
        $this->text = $text;
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
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return DateTime
     */
    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }

    /**
     * @param DateTime $timeCreated
     */
    public function setTimeCreated(DateTime $timeCreated): void
    {
        $this->timeCreated = $timeCreated;
    }

    /**
     * @return DateTime
     */
    public function getTimeLastEdit(): DateTime
    {
        return $this->timeLastEdit;
    }

    /**
     * @param DateTime $timeLastEdit
     */
    public function setTimeLastEdit(DateTime $timeLastEdit): void
    {
        $this->timeLastEdit = $timeLastEdit;
    }



}
