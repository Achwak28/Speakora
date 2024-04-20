<?php

if(isset($_POST['like_post'])){

   if($user_id != ''){
      
      $post_id = $_POST['post_id'];
      $post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
      $created_by = $_POST['created_by'];
      $created_by = filter_var($created_by, FILTER_SANITIZE_STRING);
      
      $select_post_like = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ? AND user_id = ?");
      $select_post_like->execute([$post_id, $user_id]);

      if($select_post_like->rowCount() > 0){
         $remove_like = $conn->prepare("DELETE FROM `likes` WHERE post_id = ?");
         $remove_like->execute([$post_id]);
         $message[] = 'removed from likes';
      }else{
         $add_like = $conn->prepare("INSERT INTO `likes`(user_id, post_id, created_by) VALUES(?,?,?)");
         $add_like->execute([$user_id, $post_id, $created_by]);
         $message[] = 'added to likes';
      }
      
   }else{
         $message[] = 'please login first!';
   }

}

?>
