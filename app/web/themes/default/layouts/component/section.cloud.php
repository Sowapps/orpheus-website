<?php
/**
 * @var string $title
 * @var string $icon
 * @var string $legend
 * @var string $description
 */
?>
<div class="py-5 my-5">
	<br><br>
	<div>
		<div class="opacity-25">
			<h2 class="text-center m-0 display-1 text-uppercase text-info fw-bold title-cloud"><?php echo $title; ?></h2>
		</div>
	</div>
	<div class="position-relative">
		<div class="position-absolute bg-info h-100 w-100 opacity-25">
		</div>
		<div class="position-relative container pb-4 pt-0 pt-md-4">
			
			<div class="row my-5">
				<div class="col-12 col-md-4 my-5 display-1 text-white text-center align-self-center">
					<i class="<?php echo $icon; ?> fa-3x"></i>
				</div>
				<div class="col-12 col-md-8">
					<div class="text-center display-6 fw-bold text-uppercase text-white title-cloud"><?php echo $legend; ?></div>
					<div class="bg-white rounded-4 p-5">
						<p class="lead fw-normal m-0">
							<?php echo trim($description); ?>
						</p>
					</div>
					<?php /*
Orpheus PHP Framework excels in both security and performance.
With built-in security features, it safeguards your applications against common web vulnerabilities, ensuring data integrity and user protection.
Moreover, its performance optimization tools, including caching and efficient data management, guarantee swift and responsive applications.
Orpheus prioritizes security without compromising speed, providing developers with a secure and high-performing platform for crafting robust web applications.

Orpheus PHP Framework excels in both security and performance.
Its robust security features include built-in safeguards against common web vulnerabilities, ensuring the protection of your web applications and sensitive data.
Meanwhile, Orpheus' modular design optimizes performance by allowing developers to fine-tune specific components for efficient execution.
With Orpheus, you can confidently build high-performance, secure web applications, knowing that best practices are embedded at the core, providing a strong foundation for your projects.
 */ ?>
				</div>
			</div>
		
		
		</div>
	</div>
	<br><br>
</div>
