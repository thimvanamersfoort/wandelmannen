$(() => {
	var skeletonArray = [];

	$(".skeleton").each((index, element) => {
		skeletonArray.push($(element).attr("data-id"));
	});

	console.log(skeletonArray);
});
