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
    const {
      videoType,
      videoEmbedUrl,
      elementId,
      loopVideo,
      autoplayVideo,
    } = videoWrapper.dataset;

    if (autoplayVideo === '1') {
      videoOverlay.classList.add('is-hidden', 'd-none');
    }

    if (videoType.toLowerCase() === 'youtube') {
      // GET VIDEO ID
      const videoID = videoEmbedUrl.substring(
        videoEmbedUrl.indexOf('embed/') + 6,
        videoEmbedUrl.lastIndexOf('?feature'),
      );
      // REPLACE DIV FOR VIDEO
      const playerYT = new YouTubePlayer(`player-${elementId}`, {
        videoId: videoID,
        playerVars: {
          playsinline: 1,
          loop: loopVideo,
          playlist: videoID,
          mute: autoplayVideo,
          autoplay: autoplayVideo,
        },
      });

      // TRIGGER PLAY
      document.getElementById(`playVideo-${elementId}`).addEventListener('click', () => {
        playerYT.playVideo();
      });
    }

    if (videoType.toLowerCase() === 'vimeo') {
      // GET VIDEO ID
      const videoID = videoEmbedUrl.substring(
        videoEmbedUrl.indexOf('video/') + 6,
        videoEmbedUrl.lastIndexOf('?app_id='),
      );

      // REPLACE DIV FOR VIDEO
      const videoOptions = {
        id: videoID,
        loop: loopVideo,
        muted: autoplayVideo,
        autoplay: autoplayVideo,
      };
      const player = new Player(`player-${elementId}`, videoOptions);

      // TRIGGER PLAY
      document.getElementById(`playVideo-${elementId}`).addEventListener('click', () => {
        player.play();
      });
    }
  });
});
