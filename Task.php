<?php
class Task {
  protected $id;
  protected $title;
  protected $description;
  protected $status;
  protected $assignedTo;
  protected $createdBy;

  public function __construct($id, $title, $description, $status, $assignedTo, $createdBy) {
    $this->id = $id;
    $this->title = $title;
    $this->description = $description;
    $this->status = $status;
    $this->assignedTo = $assignedTo;
    $this->createdBy = $createdBy;
  }
}
?>