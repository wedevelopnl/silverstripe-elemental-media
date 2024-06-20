import YouTubePlayer from 'youtube-player';
import Player from '@vimeo/player';

window.addEventListener('DOMContentLoaded', () => {
  const videoWrappers = document.querySelectorAll('figure.video');

  videoWrappers.forEach((videoWrapper) => {
    // REMOVE OVERLAY ON CLICK
    const videoOverlay = videoWrapper.querySelector('.video-thumbnail');
    if (videoOverlay) {
      videoOverlay.addEventListener('click', () => {
        videoOverlay.classList.add('is-hidden', 'd-none');
      });
    }

    // GET DATA
    const { videoType, videoEmbedUrl, elementId } = videoWrapper.dataset;
    const videoIDMatch = /(?:\/)([0-9]+)/.exec(videoEmbedUrl);
    if (!videoIDMatch) {
      return;
    }
    const videoID = videoIDMatch[1];

    if (videoType === 'YouTube') {
      // REPLACE DIV FOR VIDEO
      const playerYT = new YouTubePlayer(`player-${elementId}`, {
        videoId: videoID,
        playerVars: {
          playsinline: 1,
        },
      });

      // TRIGGER PLAY
      document.getElementById(`playVideo-${elementId}`).addEventListener('click', () => {
        playerYT.playVideo();
      });
    }

    if (videoType === 'Vimeo') {
      // REPLACE DIV FOR VIDEO
      const videoOptions = {
        id: videoID,
      };
      const player = new Player(`player-${elementId}`, videoOptions);

      // TRIGGER PLAY
      document.getElementById(`playVideo-${elementId}`).addEventListener('click', () => {
        player.play();
      });
    }
  });
});
