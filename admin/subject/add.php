<?php
include("../../functions.php");
include("../partials/header.php");
include("../partials/side-bar.php");



?>


<div class="container">
    <h1>Add a New Subject</h1>

    <!-- Display Errors or Success Messages -->
    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="error-messages">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="success-message">
            <p><?= htmlspecialchars($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Add Subject Form -->
    <form method="POST" action="add_subject.php">
        <label for="subject_code">Subject Code:</label>
        <input type="text" id="subject_code" name="subject_code" required>
        
        <label for="subject_name">Subject Name:</label>
        <input type="text" id="subject_name" name="subject_name" required>
        
        <button type="submit" name="add_subject">Add Subject</button>
    </form>

    <!-- Subject List -->
    <h2>Subject List</h2>
    <table>
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Option</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display all subjects
            $subjects = fetchSubjects();
            foreach ($subjects as $subject): ?>
                <tr>
                    <td><?= htmlspecialchars($subject['subject_code']) ?></td>
                    <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                    <td>
                        <a href="edit_subject.php?id=<?= $subject['id'] ?>">Edit</a>
                        <a href="delete_subject.php?id=<?= $subject['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>